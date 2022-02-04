<?php

namespace Cnpscy\DouyinDownload\Traits;

trait TraitAuthor
{
    public $uid;
    public $nick_name;
    public $original_author;
    public $unique_id;
    public $sec_uid;
    public $avatar;
    public $signature;
    public $follower_count;
    public $total_favorited;

    public function getUniqueId()
    {
        return $this->unique_id;
    }

    public function getSecUid()
    {
        return $this->sec_uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getNickname() : string
    {
        return $this->nick_name;
    }

    // 对emoji表情转义
    private function emojiEncode($str){
        $strEncode = '';

        $length = mb_strlen($str,'utf-8');

        for ($i=0; $i < $length; $i++) {
            $_tmpStr = mb_substr($str,$i,1,'utf-8');
            if(strlen($_tmpStr) >= 4){
                $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
            }else{
                $strEncode .= $_tmpStr;
            }
        }

        return $strEncode;
    }

    public function getAvatarThumb() : string
    {
        return $this->avatar_thumb;
    }

    public function getSignature() : string
    {
        return $this->signature;
    }

    public function getFollowerCount() : int
    {
        return $this->follower_count;
    }

    public function getTotalFavorited() : int
    {
        return $this->total_favorited;
    }

    /**
     * Get the original author array.
     *
     * @return array
     */
    public function getOriginalAuthor() : array
    {
        return $this->original_author;
    }

    /**
     * Get the original author array.
     *
     * @return array
     */
    public function setOriginalAuthor(array $author) : void
    {
        $this->original_author = $author;
    }

    /**
     * Map the given array onto the author's properties.
     *
     * @param  array  $attributes
     *
     * @return $this
     */
    public function mapAuthor(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    // 作者信息
    public $author = [];

    public function getAuthor() : array
    {
        return $this->author;
    }

    public function setAuthor($author) : array
    {
        $this->setOriginalAuthor(isset($author['original_author']) ? $author['original_author'] : []);
        $this->mapAuthor($author);
        return $this->author = $author;
    }
}