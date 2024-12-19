<?php

namespace controllers;
use models\Comment;

class CommentController {
    private $controllerModel;
    public function __construct(Comment $comment)
    {
        $this->commentModel = $comment;
    }
    public function index(Comment $comment) {
        return  $comment->all();
    }
}