<?php

require_once __DIR__ . "/BaseORM.php";

class ForumORM extends BaseORM
{
    protected $table = "Forum";
    protected $primaryKey = "id_forum";
}
