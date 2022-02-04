<?php

namespace Cnpscy\DouyinDownload\Interfaces;

interface InterfaceAuthor
{
    public function getUniqueId();

    public function getSecUid();

    public function getUid();

    public function getNickname() : string;

    public function getAvatarThumb() : string;

    public function getSignature() : string;

    public function getOriginalAuthor() : array;

    public function getFollowerCount() : int;

    public function getTotalFavorited() : int;
}