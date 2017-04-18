<?php

class bdImage_XenForo_ViewPublic_Thread_View extends XFCP_bdImage_XenForo_ViewPublic_Thread_View
{
    public function renderHtml()
    {
        parent::renderHtml();

        $this->_bdImage_hideThreadImageAttachments();
    }

    protected function _bdImage_hideThreadImageAttachments()
    {
        if (!bdImage_Option::get('template', 'thread_view_hidden_attachment')) {
            return;
        }

        if (empty($this->_params['thread']['bdimage_image'])) {
            return;
        }
        $threadRef =& $this->_params['thread'];

        if (empty($threadRef['first_post_id'])) {
            return;
        }
        $firstPostId = $threadRef['first_post_id'];

        if (empty($this->_params['posts'][$firstPostId])) {
            return;
        }
        $firstPostRef =& $this->_params['posts'][$firstPostId];

        if (empty($firstPostRef['attachments'])) {
            return;
        }
        $firstPostAttachmentsRef =& $firstPostRef['attachments'];

        $threadImageData = bdImage_Helper_Data::unpack($this->_params['thread']['bdimage_image']);
        $threadImageUrls = array($threadImageData['url']);
        if (!empty($threadImageData[bdImage_Helper_Data::SECONDARY_IMAGES])) {
            foreach ($threadImageData[bdImage_Helper_Data::SECONDARY_IMAGES] as $secondaryImage) {
                $threadImageUrls[] = bdImage_Helper_Data::get($secondaryImage, bdImage_Helper_Data::IMAGE_URL);
            }
        }

        foreach (array_keys($firstPostAttachmentsRef) as $attachmentId) {
            $attachmentUrls = array(
                XenForo_Link::buildPublicLink('canonical:attachments', $firstPostAttachmentsRef[$attachmentId]),
                XenForo_Link::buildPublicLink('full:attachments', $firstPostAttachmentsRef[$attachmentId]),
            );

            $isThreadImage = false;
            foreach ($attachmentUrls as $attachmentUrl) {
                if (in_array($attachmentUrl, $threadImageUrls, true)) {
                    $isThreadImage = true;
                    break;
                }
            }

            if ($isThreadImage) {
                unset($firstPostAttachmentsRef[$attachmentId]);
                continue;
            }
        }
    }
}