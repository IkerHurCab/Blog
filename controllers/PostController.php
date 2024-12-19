<?php
namespace controllers;
use models\Post;
class PostController
{
    private $postModel;
    public function __construct(Post $post)
    {
        $this->postModel = $post;
    }
    
    
}