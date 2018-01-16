<?php
class DbHandler {
    private $conn;
    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
	
	public function getAllUsers() {
        $users = array();
        $stmt = $this->conn->prepare("SELECT user_id, name, access, profilepic, lastLogin FROM users");
        $stmt->execute();
        $usersQuery = $stmt->get_result();
        while ($user = $usersQuery->fetch_assoc()) {
                $tmp = array();
				$tmp["user_id"] = $user["user_id"];
				$tmp["name"] = $user["name"];
				$tmp["access"] = $user["access"];
				$tmp["profilepic"] = $user["profilepic"];
				$tmp["lastLogin"] = $user["lastLogin"];
                array_push($users, $tmp);
            }
		$stmt->close();
        return $users;
    }
	
	public function getAllCourseTasks($cid) {
		$cid = intval($cid);
        $tasks = array();
        $stmt = $this->conn->prepare("SELECT task_id, name, description, maxNote, acceptedFile, deadline, timeAdded FROM coursetask WHERE course_id = ?");
        $stmt->bind_param("i", $cid);
        $stmt->execute();
        $taskQuery = $stmt->get_result();
        while ($task = $taskQuery->fetch_assoc()) {
                $tmp = array();
				$tmp["task_id"] = $task["task_id"];
				$tmp["name"] = $task["name"];
				$tmp["description"] = $task["description"];
				$tmp["maxNote"] = $task["maxNote"];
				$tmp["acceptedFile"] = $task["acceptedFile"];
				$tmp["deadline"] = $task["deadline"];
				$tmp["timeAdded"] = $task["timeAdded"];
                array_push($tasks, $tmp);
            }
		$stmt->close();
        return $tasks;
    }
	
	public function getAllCourses() {
        $courses = array();
        $stmt = $this->conn->prepare("SELECT course_id, name, descr, owner, created FROM courses");
        $stmt->execute();
        $coursesQuery = $stmt->get_result();
        while ($course = $coursesQuery->fetch_assoc()) {
                $tmp = array();
				$tmp["course_id"] = $course["course_id"];
				$tmp["name"] = $course["name"];
				$tmp["descr"] = $course["descr"];
				$tmp["owner"] = $course["owner"];
				$tmp["created"] = $course["created"];
                array_push($courses, $tmp);
            }
		$stmt->close();
        return $courses;
    }
	
	public function getUserCourses($uid = null) {	
		$courses = array();
        $stmt = $this->conn->prepare("SELECT course_id from userCourses WHERE user_id = ?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $coursesQuery = $stmt->get_result();
        while ($course = $coursesQuery->fetch_assoc()) {
                array_push($courses, $this->courseData($course["course_id"]));
            }
		$stmt->close();
        return $courses;
    }
	
	public function getTeacherCourses($uid = null) {	
		$courses = array();
        $stmt = $this->conn->prepare("SELECT course_id from courses WHERE owner = ?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $coursesQuery = $stmt->get_result();
        while ($course = $coursesQuery->fetch_assoc()) {
                array_push($courses, $this->courseData($course["course_id"]));
            }
		$stmt->close();
        return $courses;
    }
	
	public function updatePhoto($uid, $imagename) {
        $stmt = $this->conn->prepare("UPDATE users SET profilepic = ? WHERE user_id = ?");
		$stmt->bind_param("si", $imagename, $uid);
		$stmt->execute();
		$stmt->close();
	}	
	
	public function setUserAccess($uid, $access) {	
        $stmt = $this->conn->prepare("UPDATE users SET access = ? WHERE user_id = ?");
		$stmt->bind_param("ii", $access, $uid);
		$stmt->execute();
		$stmt->close();
	}
	
	public function signToCourse($cid, $password, $uid) {
		$cid = intval($cid);
		$uid = intval($uid);
        $response = array();
        if ($this->validateCoursePassword($cid,$password)) {
			
			if (!$this->isUserInCourse($uid, $cid)) {
				$stmt = $this->conn->prepare("INSERT INTO userCourses(user_id, course_id) values(?, ?)");
				$stmt->bind_param("ii", $uid, $cid);
				$result = $stmt->execute();
				$stmt->close();
				if ($result) {
					$response["error_code"] = 0;
					$response["message"] = "SUCCESS";
				} else {
					$response["error_code"] = 1;
					$response["message"] = "Undefined error";
				}
			} else {
					$response["error_code"] = 2;
					$response["message"] = "User already in course";
			}
		}
		else {
			
					$response["error_code"] = 1;
					$response["message"] = "Zle haslo";
		}
        return $response;
    }	
	
	public function getTaskAnswersById($tid) {
		$taskAnswers = array();
        $stmt = $this->conn->prepare("SELECT id, user_id, note, filename, timeAdded, timeChecked FROM userTasks WHERE task_id = ?");
		$stmt->bind_param("i", $tid);
        $stmt->execute();
        $answerQuery = $stmt->get_result();
        while ($answer = $answerQuery->fetch_assoc()) {
                $tmp = array();
				$tmp["answer_id"] = $answer["id"];
				$tmp["user_id"] = $answer["user_id"];
				$tmp["note"] = $answer["note"];
				$tmp["filename"] = $answer["filename"];
				$tmp["timeAdded"] = $answer["timeAdded"];
				$tmp["timeChecked"] = $answer["timeChecked"];
                array_push($taskAnswers, $tmp);
            }
		$stmt->close();
        return $taskAnswers;
	}
	
	public function getUserCourseTasksAnswers($uid, $cid) {
		$taskAnswers = array();
       // $stmt = $this->conn->prepare("SELECT id, task_id, user_id, note, filename, timeAdded, timeChecked FROM userTasks WHERE course_id = ? SORT BY timeAdded DESC");
		$stmt = $this->conn->prepare("SELECT id, task_id, note, timeChecked FROM userTasks WHERE course_id = ? AND user_id = ? ORDER BY task_id DESC");
		
		$stmt->bind_param("ii", $cid, $uid);
        $stmt->execute();
        $answerQuery = $stmt->get_result();
        while ($answer = $answerQuery->fetch_assoc()) {
                $tmp = array();
				$tData = $this->taskData($answer["task_id"]);
				$tmp["answer_id"] = $answer["id"];
				$tmp["name"] = $tData["name"];
				$tmp["maxNote"] = $tData["maxNote"];
				$tmp["note"] = $answer["note"];
				$tmp["timeChecked"] = $answer["timeChecked"];
                array_push($taskAnswers, $tmp);
            }
		$stmt->close();
        return $taskAnswers;
	}
	
	
	public function setTaskAnswerNote($aid, $note) {
		$stmt = $this->conn->prepare("UPDATE userTasks SET note = ?, timeChecked = NOW() WHERE id = ? AND note = -1");
		$stmt->bind_param("ii", $note, $aid);
		$stmt->execute();
		$stmt->close();
	}
	
	public function addTaskAnswer($tid, $cid, $filename) {
		$uid = $_SESSION['user_id'];
		$uid = intval($uid);
		$tid = intval($tid);
		$cid = intval($cid);
		$stmt = $this->conn->prepare("INSERT INTO userTasks(task_id, course_id, user_id, filename) values(?, ?, ?, ?)");
        $stmt->bind_param("iiis", $tid, $cid, $uid, $filename);
        $result = $stmt->execute();
        $stmt->close();
        
	}
	
	public function hasGivenTask($tid, $uid) {
        $stmt = $this->conn->prepare("SELECT id from userTasks WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tid, $uid);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	
	public function isUserInCourse($uid, $cid) {
		$uid = intval($uid);
		$cid = intval($cid);
		$stmt = $this->conn->prepare("SELECT course_id from userCourses WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $uid, $cid);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;		
	}
	
	private function validateCoursePassword($id, $password) {
		$stmt = $this->conn->prepare("SELECT course_id from courses WHERE course_id = ? AND password = ?");
        $stmt->bind_param("is", $id, $password);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/*
	********************Data getters
	*/
	
	public function data($uid = null) {
        if ($uid === null) {
            $uid = $_SESSION['user_id'];
        }
        $uid = intval($uid);
        $stmt = $this->conn->prepare("SELECT * from users WHERE user_id = ?");
        $stmt->bind_param("i", $uid);
        if ($stmt->execute()) {
			$user = $stmt->get_result()->fetch_assoc();
		}
        return $user;
    }
	
	public function courseData($id) {
        $id = intval($id);
        $stmt = $this->conn->prepare("SELECT * from courses WHERE course_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
			$course = $stmt->get_result()->fetch_assoc();
		}
        return $course;
    }
	
	public function taskData($tid) {
        $tid = intval($tid);
        $stmt = $this->conn->prepare("SELECT * from coursetask WHERE task_id = ?");
        $stmt->bind_param("i", $tid);
        if ($stmt->execute()) {
			$course = $stmt->get_result()->fetch_assoc();
		}
        return $course;
    }
	
	public function taskAnswerData($tid, $uid) {
        $stmt = $this->conn->prepare("SELECT * from userTasks WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tid, $uid);
        if ($stmt->execute()) {
			$taskAnswer = $stmt->get_result()->fetch_assoc();
		}
        return $taskAnswer;
	}
	
	/*
	********************Tworzenie Zadań
	*/
	
	public function createTask($cid, $name, $description, $maxNote, $acceptedFile, $deadline) {
        $response = array();
		$cid = intval($cid);
		$maxNote = intval($maxNote);
		$acceptedFile = intval($acceptedFile);
        if (!$this->courseExists($name)) {
            $stmt = $this->conn->prepare("INSERT INTO coursetask(course_id, name, description, maxNote, acceptedFile, deadline) values(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issiis", $cid, $name, $description, $maxNote, $acceptedFile, $deadline);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                $response["error_code"] = 0;
				$response["message"] = "SUCCESS";
            } else {
                $response["error_code"] = 1;
                $response["message"] = "Undefined error";
            }
        } else {
				$response["error_code"] = 2;
				$response["message"] = "Task already exist";
		}
        return $response;
    }
		
	/*
	********************Tworzenie kursów
	*/
	
	public function createCourse($name, $desc, $password, $ownerId) {
        $response = array();
		$ownerId = intval($ownerId);
        if (!$this->courseExists($name)) {
            $stmt = $this->conn->prepare("INSERT INTO courses(name, descr, password, owner) values(?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $desc, $password, $ownerId);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                $response["error_code"] = 0;
				$response["message"] = "SUCCESS";
            } else {
                $response["error_code"] = 1;
                $response["message"] = "Undefined error";
            }
        } else {
				$response["error_code"] = 2;
				$response["message"] = "Course already exist";
		}
        return $response;
    }
		
	private function courseExists($name) {
        $stmt = $this->conn->prepare("SELECT course_id from courses WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/*
	********************Rejestracja/logowanie
	*/
	
	public function createUser($name, $password, $email, $access) {
        $response = array();

        if (!$this->userExists($name)) {
            $stmt = $this->conn->prepare("INSERT INTO users(name, password, email, access) values(?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $password, $email, $access);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                $response["error_code"] = 0;
				$response["message"] = "SUCCESS";
            } else {
                $response["error_code"] = 1;
                $response["message"] = "Undefined error";
            }
        } else {
				$response["error_code"] = 2;
				$response["message"] = "User already exist";
		}
        return $response;
    }	
	
	private function userExists($name) {
        $stmt = $this->conn->prepare("SELECT user_id from users WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	public function loginUser($name, $password) {
        $response = array();
        $stmt = $this->conn->prepare("SELECT user_id, name, email, access, profilepic, lastLogin from users WHERE name = ? AND password = ?");
        $stmt->bind_param("ss", $name, $password);
        $result = $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
		
		$user = array();
        if ($num_rows > 0) {
			$stmt->bind_result($user_id, $name, $email, $access, $profilepic, $lastLogin);
            $stmt->fetch();
			$user["lastLogin"] = $lastLogin;
			$this->updateLastLogin($user_id);
			$user["error"] = false;
            $user["user_id"] = $user_id;
            $user["name"] = $name;
			$user["email"] = $email;
			$user["profilepic"] = $profilepic;
			$user["access"] = $access;
 		}
		else {
			$user["error"] = true;
		}
		$stmt->close();
		return $user;
    }
	
	private function updateLastLogin($uid) {
        $stmt = $this->conn->prepare("UPDATE users SET lastLogin = NOW() WHERE user_id = ?");
		 $stmt->bind_param("i", $uid);
		$stmt->execute();
		$stmt->close();
	}
	
	/*
	********************Komunikator
	*/
	
	public function getShouts() {
        $shouts = array();
        $stmt = $this->conn->prepare("SELECT uid, text, date FROM shoutbox ORDER BY date DESC LIMIT 10");
        $stmt->execute();
        $shoutQuery = $stmt->get_result();
        while ($shout = $shoutQuery->fetch_assoc()) {
                $tmp = array();
				$tmp["uid"] = $shout["uid"];
				$tmp["text"] = $shout["text"];
				$tmp["date"] = $shout["date"];
				$user = $this->data(intval($shout["uid"]));
				$tmp["name"] = $user["name"];
				$tmp["access"] = $user["access"];
                array_push($shouts, $tmp);
            }
		$stmt->close();
        return $shouts;
    }	
	
	public function shout($text) {
        $uid = $_SESSION['user_id'];
		$uid = intval($uid);
		$stmt = $this->conn->prepare("INSERT INTO shoutbox(uid, text) values(?, ?)");
        $stmt->bind_param("is", $uid, $text);
        $result = $stmt->execute();
        $stmt->close();
        }
}
?>
