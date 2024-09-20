<?php

require_once('db_connection.php');  // Make sure this path is correct

class Connection
{
    public $pdo = null;

    public function __construct()
    {
        try {
            // Use the connection from the function
            $this->pdo = connectToDatabase();  // This will return the PDO connection
        } catch (PDOException $exception) {
            echo "ERROR: " . $exception->getMessage();
        }
    }

    public function getNotes($username)
    {
        $statement = $this->pdo->prepare("SELECT * FROM notes WHERE username = :username AND pinned = 'no' ORDER BY create_date DESC");
        $statement->bindValue(':username', $username);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getpinNotes($username)
    {
        $statement = $this->pdo->prepare("SELECT * FROM notes WHERE username = :username AND pinned = 'yes' ORDER BY create_date DESC");
        $statement->bindValue(':username', $username);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addNote($note)
    {
        $datetime = new DateTime('now', new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Asia/Singapore'));
        $gmt8_time = $datetime->format('Y-m-d H:i:s');

        $statement = $this->pdo->prepare("INSERT INTO notes (title, description, create_date, pinned, username)
                                    VALUES (:title, :description, :date, :pinned, :username)");
        $statement->bindValue('title', $note['title']);
        $statement->bindValue('description', $note['description']);
        $statement->bindValue('date', $gmt8_time);
        $statement->bindValue('pinned', $note['pinned']);
        $statement->bindValue('username', $note['username']);
        return $statement->execute();
    }

    public function updateNote($id, $note)
    {
        $statement = $this->pdo->prepare("UPDATE notes SET title = :title, description = :description WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->bindValue('title', $note['title']);
        $statement->bindValue('description', $note['description']);
        return $statement->execute();
    }

    public function pinNote($id, $pinned)
    {
        try {
            $statement = $this->pdo->prepare("UPDATE notes SET pinned = :pinned WHERE id = :id");
            $statement->bindValue(':pinned', $pinned);
            $statement->bindValue(':id', $id);
            $statement->execute();
            return true;
        } catch (PDOException $exception) {
            echo "ERROR: " . $exception->getMessage();
            return false;
        }
    }

    public function removeNote($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM notes WHERE id = :id");
        $statement->bindValue('id', $id);
        return $statement->execute();
    }

    public function getNoteById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM notes WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}

return new Connection();
