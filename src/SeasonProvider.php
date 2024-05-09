<?php

namespace App;

use App\Entity\Entity;
use App\Entity\Video;

class SeasonProvider
{
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function create(Entity $entity)
    {
        $seasons = $entity->getSeasons();

        if (empty($seasons)) {
            return '';
        }

        $seasonsHtml = '';

        foreach ($seasons as $season) {
            $seasonNumber = $season->getSeasonNumber();
            $videosHtml = '';

            foreach ($season->getVideos() as $video) {
                $videosHtml .= $this->createVideoSquare($video);
            }

            $seasonsHtml .= "<div class='season'>
                                <h3>Season $seasonNumber</h3>
                                <div class='videos'>
                                    $videosHtml
                                </div>
                            </div>";
        }

        return $seasonsHtml;
    }

    private function createVideoSquare(Video $video)
    {
        $id = $video->getId();
        $name = $video->getTitle();
        $description = $video->getDescription();
        $thumbnail = $video->getThumbnail();
        $episodeNumber = $video->getEpisodeNumber();
        $hasSeen = $video->hasSeen($this->username) ? "<i class='fas fa-check-circle seen'></i>" : '';

        return "<a href='watch.php?id=$id'>
                    <div class='episodeContainer'>
                        <div class='contents'>
                            <img src='$thumbnail'>
                            <div class='videoInfo'>
                                <h4>$episodeNumber. $name</h4>
                                <span>$description</span>
                            </div>
                            $hasSeen
                        </div>
                    </div>
                </a>";
    }
}