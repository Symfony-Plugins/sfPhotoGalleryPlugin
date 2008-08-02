<?php

/**
 * Subclass for performing query and update operations on the 'photo' table.
 *
 * 
 *
 * @package lib.model
 */ 
class sfPhotoGalleryPeer extends BasesfPhotoGalleryPeer
{
  public static function hasGallery($entity,$entity_id) {
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$entity);
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$entity_id);
    if (sfPhotoGalleryPeer::doCount($c)>0) return true;
    return false;
  }
  
  public static function getFirst($entity,$entity_id) {
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$entity);
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$entity_id);
    $photo=sfPhotoGalleryPeer::doSelectOne($c);
    return $photo->getRealName();
  }
  
  public static function getLightboxArray($entity,$entity_id) {
    $images=array();
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$entity);
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$entity_id);
    $photos=sfPhotoGalleryPeer::doSelect($c);
    if (count($photos)>0) {
      foreach ($photos as $photo) {
	$images[]=array(
			'real_name'=>$photo->getRealName(),
			'options'=>array('title'=>$photo->getDescription())
			);
      }
    }
    return $images;
  }
}
