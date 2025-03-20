<?php

class BreadcrumbHelper
{
    private static $siteName = "Noot Forums";

    public static function getSiteName(): string
    {
        return self::$siteName;
    }

    /**
     * @param string $route
     * @param array $params
     */
    public static function getBreadcrumbs($route, $params = []): ?array
    {
        $breadcrumbs = [["title" => self::$siteName, "url" => "home"]];

        switch ($route) {
            case "forum":
                if (isset($params["id"])) {
                    $forumData = (new ForumModel())->getForum($params["id"]);
                    if ($forumData) {
                        $breadcrumbs[] = [
                            "title" => $forumData["title"],
                            "url" => "forum?id={$forumData["id_forum"]}",
                        ];
                    }
                }
                break;

            case "thread":
                if (isset($params["id"])) {
                    $threadData = (new PostsORM())->findById($params["id"]);
                    if ($threadData) {
                        $forumData = (new ForumModel())->getForum(
                            $threadData["id_forum"]
                        );
                        if ($forumData) {
                            $breadcrumbs[] = [
                                "title" => $forumData["title"],
                                "url" => "forum?id={$forumData["id_forum"]}",
                            ];
                            $breadcrumbs[] = [
                                "title" => $threadData["title"],
                                "url" => null,
                            ];
                        }
                    }
                }
                break;

            case "panel":
                $breadcrumbs[] = [
                    "title" => "Admin Panel",
                    "url" => null,
                ];
                break;

            case "login":
                $breadcrumbs[] = [
                    "title" => "Login",
                    "url" => null,
                ];
                break;

            case "register":
                $breadcrumbs[] = [
                    "title" => "Register",
                    "url" => null,
                ];
                break;

            case "users":
                $breadcrumbs[] = [
                    "title" => "Users",
                    "url" => null,
                ];
                break;

            case "settings":
                $breadcrumbs[] = [
                    "title" => "Settings",
                    "url" => null,
                ];
                break;

            case "privacy-policy":
                $breadcrumbs[] = [
                    "title" => "Privacy Policy",
                    "url" => null,
                ];
                break;

            case "tos":
                $breadcrumbs[] = [
                    "title" => "Terms of Service",
                    "url" => null,
                ];
                break;

            case "rules":
                $breadcrumbs[] = [
                    "title" => "Rules",
                    "url" => null,
                ];
                break;

            case "comment":
                if (isset($params["id"])) {
                    $commentData = (new CommentModel())->getComment(
                        $params["id"]
                    );
                    if ($commentData) {
                        $threadData = (new PostsORM())->findById(
                            $commentData["id_post"]
                        );
                        if ($threadData) {
                            $forumData = (new ForumModel())->getForum(
                                $threadData["id_forum"]
                            );
                            if ($forumData) {
                                $breadcrumbs[] = [
                                    "title" => $forumData["title"],
                                    "url" => "forum?id={$forumData["id_forum"]}",
                                ];
                                $breadcrumbs[] = [
                                    "title" => $threadData["title"],
                                    "url" => "thread?id={$threadData["id_post"]}",
                                ];
                                $breadcrumbs[] = [
                                    "title" => "Edit Comment",
                                    "url" => null,
                                ];
                            }
                        }
                    }
                }
                break;
        }

        return $breadcrumbs;
    }
}
