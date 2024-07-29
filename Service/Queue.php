<?php

class Queue {
    private $queueFile;

    public function __construct($queueFile) {
        $this->queueFile = $queueFile;
    }

    public function addTask($task) {
        $tasks = [];
        if (file_exists($this->queueFile)) {
            $tasks = json_decode(file_get_contents($this->queueFile), true);
        }
        $tasks[] = $task;
        file_put_contents($this->queueFile, json_encode($tasks));
    }

    public function getTasks() {
        if (file_exists($this->queueFile)) {
            return json_decode(file_get_contents($this->queueFile), true);
        }
        return [];
    }

    public function clearTasks() {
        file_put_contents($this->queueFile, json_encode([]));
    }
}
?>
