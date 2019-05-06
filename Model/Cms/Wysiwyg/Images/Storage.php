<?php
 
namespace Bay20\CMSpdfallowed\Model\Cms\Wysiwyg\Images;
 
class Storage extends \Magento\Cms\Model\Wysiwyg\Images\Storage
{
    public function uploadFile($targetPath, $type = null)
    {
        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);
        $allowed = $this->getAllowedExtensions($type);
        if ($allowed) {
            $uploader->setAllowedExtensions($allowed);
        }
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $result = $uploader->save($targetPath);
 
        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t upload the file right now.'));
        }
 
            // Change Start
        if (strtolower($uploader->getFileExtension()) !== 'pdf') {
            // Create Thumbnail
            $this->resizeFile($targetPath . '/' . $uploader->getUploadedFileName(), true);
        }
 
        $result['cookie'] = [
            'name' => $this->getSession()->getName(),
            'value' => $this->getSession()->getSessionId(),
            'lifetime' => $this->getSession()->getCookieLifetime(),
            'path' => $this->getSession()->getCookiePath(),
            'domain' => $this->getSession()->getCookieDomain(),
        ];
 
        return $result;
    }
}