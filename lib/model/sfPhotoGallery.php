<?php

/**
 *
 * @package lib.model
 */ 
class sfPhotoGallery extends BasesfPhotoGallery
{
  // RANK MANAGING
  public function save($conn=null) {
    if(!$this->getRank()>0) {
      // Recupera il rank più alto
      $c=new Criteria();
      $c->addDescendingOrderByColumn(sfPhotoGalleryPeer::RANK);
      if(sfPhotoGalleryPeer::doCount($c)==0) {
	$rank=0;
      }
      else {
	$last_photo=sfPhotoGalleryPeer::doSelectOne($c);
	$rank=$last_photo->getRank()+1;
      }
      $this->setRank($rank);
    }
    parent::save($conn);
  }
  public function getRealName() {
    return $this->getId().'.'.$this->getSuffix();
  }
  public function getImageName() {
    return '/sfPhotoGalleryPlugin/'.$this->getRealName();
  }
  public function getThumbName() {
    return '/sfPhotoGalleryPlugin/thumbnails/'.$this->getRealName();
  }
  public function getImagePath() {
    return SF_ROOT_DIR.'/web/sfPhotoGalleryPlugin/'.$this->getRealName();
  }
  public function getThumbPath() {
    return SF_ROOT_DIR.'/web/sfPhotoGalleryPlugin/thumbnails/'.$this->getRealName();
  }

  public function getMaxRank() {
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->getEntity());
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->getEntityId());
    $c->addDescendingOrderByColumn(sfPhotoGalleryPeer::RANK);
    $photo_max=sfPhotoGalleryPeer::doSelectOne($c);
    return $photo_max->getRank();
  }

  public function getMinRank() {
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->getEntity());
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->getEntityId());
    $c->addAscendingOrderByColumn(sfPhotoGalleryPeer::RANK);
    $photo_min=sfPhotoGalleryPeer::doSelectOne($c);
    return $photo_min->getRank();
  }

  public function isLast() {
    if($this->getMaxRank()==$this->getRank()) 
      return true;
    return false;
  }
  public function isFirst() {
    if($this->getMinRank()==$this->getRank()) 
      return true;
    return false;
  }
  public function moveUp() {
    if ($this->isFirst()) return;
    if ($this->countPhotos()<=1) return;
    // Fai lo swap con il precedente
    $this_rank=$this->getRank();
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->getEntity());
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->getEntityId());
    $c->add(sfPhotoGalleryPeer::RANK,$this_rank,Criteria::LESS_THAN);
    $c->addDescendingOrderByColumn(sfPhotoGalleryPeer::RANK);
    $prev_photo=sfPhotoGalleryPeer::doSelectOne($c);
    $prev_rank=$prev_photo->getRank();

    // Esegui lo swap
    $this->setRank($prev_rank);
    $this->save();
    $prev_photo->setRank($this_rank);
    $prev_photo->save();
  }
  public function moveDown() {
    if ($this->isLast()) return;
    if ($this->countPhotos()<=1) return;
    // Fai lo swap con il precedente
    $this_rank=$this->getRank();
    $c=new Criteria();
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->getEntity());
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->getEntityId());
    $c->add(sfPhotoGalleryPeer::RANK,$this_rank,Criteria::GREATER_THAN);
    $c->addAscendingOrderByColumn(sfPhotoGalleryPeer::RANK);
    $next_photo=sfPhotoGalleryPeer::doSelectOne($c);
    $next_rank=$next_photo->getRank();
    
    // Esegui lo swap
    $this->setRank($next_rank);
    $this->save();
    $next_photo->setRank($this_rank);
    $next_photo->save();
  }
  // Conta tutte le foto della fotogallery alla quale appartiene la foto
  public function countPhotos() {
    $c= new Criteria() ;
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->getEntity());
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->getEntityId());
    return sfPhotoGalleryPeer::doCount($c);
  }
  
}
