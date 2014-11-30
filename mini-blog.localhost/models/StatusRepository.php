<?php

class StatusRepository extends DbRepository
{
	public function insert($user_id,$body)
	{
		$now = new DateTime();

		$sql = "INSERT INTO status(user_id,body,created_at) VALUES(:user_id,:body,:created_at)";

		$stmt = $this->execute($sql,array(
			':user_id'		=> $user_id,
			':body'			=> $body,
			':created_at'	=> $now->format('Y-m-d H:i:s'),
		));
	}


	public function fetchAllPersonalArchivesByUserId($user_id)
	{
		$sql = "SELECT a.*, u.user_name FROM status a 
			LEFT JOIN user u ON a.user_id = u.id 
				LEFT JOIN following f ON f.following_id = a.user_id 
					AND f.user_id = :user_id 
						WHERE f.user_id = :user_id OR u.id = :user_id ORDER BY a.created_at DESC";

		return $this->fetchAll($sql,array(':user_id' => $user_id));
	}


	public function fetchAllByUserId($user_id)
	{
		$sql = "SELECT a.*, u.user_name FROM status a 
			LEFT JOIN user u ON a.user_id = u.id 
				WHERE u.id = :user_id 
					ORDER BY a.created_at DESC";


		return $this->fetchAll($sql,array(':user_id' => $user_id));
	}

	/*public function fetchAllRecentStatuses()
	{
		$sql = "SELECT a.*, u.user_name FROM status a 
				LEFT JOIN user u ON u.id = a.user_id
				 ORDER BY created_at  DESC LIMIT 20";
 
		return $this->fetchAll($sql);
	}*/

	public function fetchByIdAndUserName($id,$user_name)
	{
		$sql = "SELECT a.*,u.user_name FROM status a LEFT JOIN user u ON u.id = a.user_id
					WHERE a.id = :id AND u.user_name = :user_name";
		
		return $this->fetch($sql,array(
			':id'		=> $id,
			':user_name'	=> $user_name,
		));
	}

	public function fetchByBodyContents($user_id)
	{
		$sql = "SELECT a.*,u.user_name FROM status a LEFT JOIN user u ON a.user_id = u.id WHERE a.user_id NOT IN(SELECT following_id FROM following WHERE user_id = :user_id) AND user_id != :user_id";

		return $this->fetchAll($sql,array(
			':user_id' => $user_id
		));
	}

}