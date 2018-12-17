<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 15.12.18
 * Time: 21:01
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Creators;
use App\Models\Posts;
use Exception;
use Illuminate\Console\Command;

ini_set('memory_limit','2048M');

/**
 * Class ParseRssCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class ParseRssCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "ParseRssCommand";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Parse the Rss";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $rss = new \DOMDocument();
            $rss->load('https://dev98.de/feed/');
            $feeds = array();

            foreach ($rss->getElementsByTagName('item') as $node) {
                $item = array (
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                    'creator' => $node->getElementsByTagName('creator')->item(0)->nodeValue,
                );

                $categories = $node->getElementsByTagName('category');
                if ($categories->length > 0) {
                    foreach ($categories as $key => $category) {
                        if ($key == 0) {
                            $item['category'] = $category->nodeValue;
                        } else {
                            $item['tags'][] = $category->nodeValue;
                        }
                    }
                }

                $item['description'] = $node->getElementsByTagName('description')->item(0)->nodeValue;

                $item['content'] = '';

                $content = $node->getElementsByTagName('encoded');

                if ($content->length > 0) {
                    $item['content'] = $content->item(0)->nodeValue;
                }

                $item['commentsCount'] = 0;

                $commentRssLink = $node->getElementsByTagName('commentRss')->item(0)->nodeValue;
                $commentRss = new \DOMDocument();
                $commentRss->load($commentRssLink);

                $comments = $commentRss->getElementsByTagName('item');

                if ($comments->length > 0) {

                    $item['commentsCount'] = $comments->length;

                    foreach ($commentRss->getElementsByTagName('item') as $commentNode) {
                        $comment = array(
                            'title' => $commentNode->getElementsByTagName('title')->item(0)->nodeValue,
                            'link' => $commentNode->getElementsByTagName('link')->item(0)->nodeValue,
                            'date' => $commentNode->getElementsByTagName('pubDate')->item(0)->nodeValue,
                            'creator' => $commentNode->getElementsByTagName('creator')->item(0)->nodeValue,
                            'description' => $commentNode->getElementsByTagName('description')->item(0)->nodeValue
                        );

                        $commentContent = $commentNode->getElementsByTagName('encoded');

                        $comment['content'] = '';

                        if ($commentContent->length > 0) {
                            $comment['content'] = $commentContent->item(0)->nodeValue;
                        }

                        $item['comments'][] = $comment;
                    }
                }

                array_push($feeds, $item);
            }


            foreach ($feeds as $feed) {
                // Create or update PostCreator
                $creator = Creators::updateOrCreate(
                    ['name' => $feed['creator']],
                    ['is_active' => 1]
                );

                $timestamp = strtotime($feed['date']);

                // Create or update Post
                $post = Posts::updateOrCreate(
                    [
                        'post_title' => $feed['title'],
                        'post_link' => $feed['link'],
                        'creators_id' => $creator->id,
                        'created_at' => date("Y-m-d H:i:s", $timestamp)
                    ],
                    [
                        'post_description' => $feed['description'],
                        'post_content' => $feed['content'],
                        'post_comments_count' => $feed['commentsCount']
                    ]
                );

                if ($feed['commentsCount'] > 0) {
                    foreach ($feed['comments'] as $comment) {
                        // Create or update PostCommentCreator
                        $commentCreator = Creators::updateOrCreate(
                            ['name' => $comment['creator']],
                            ['is_active' => 1]
                        );

                        $timestamp = strtotime($comment['date']);

                        // Creator or update PostComment
                        $postComment = Comments::updateOrCreate(
                            [
                                'comment_title' => $comment['title'],
                                'comment_link' => $comment['link'],
                                'creators_id' => $commentCreator->id,
                                'post_id' => $post->id,
                                'created_at' => date("Y-m-d H:i:s", $timestamp)
                            ],
                            [
                                'comment_description' => $comment['description'],
                                'comment_content' => $comment['content']
                            ]
                        );
                    }
                }
            }

            $this->info("All posts have been parsed and saved");
        } catch (Exception $e) {
            print_r($e->getMessage());
            $this->error("An error occurred");
        }
    }
}