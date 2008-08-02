<?php
// auto-generated by sfPropelCrud
// date: 2008/04/21 22:32:09
?>
<?php

require_once(SF_ROOT_DIR.'/plugins/sfPhotoGalleryPlugin/lib/sfPhotoGalleryDefault.php');

/**
 * photo actions.
 *
 * @package    micar
 * @subpackage photo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class sfPhotoGalleryActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('sfPhotoGallery', 'list');
  }

  public function executeList()
  {
    $this->photos = sfPhotoGalleryPeer::doSelect(new Criteria());
  }

  public function executeShow()
  {
    $this->photo = sfPhotoGalleryPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->photo);
  }

  public function executeCreate()
  {
    $this->photo = new sfPhotoGallery();
  }

  public function executeManageGallery()
  {
    $c=new Criteria();
    $this->entity=$this->getRequestParameter('entity');
    $this->entity_id=$this->getRequestParameter('entity_id');
    $c->add(sfPhotoGalleryPeer::ENTITY_ID,$this->entity_id);
    $c->add(sfPhotoGalleryPeer::ENTITY,$this->entity);
    $c->addAscendingOrderByColumn(sfPhotoGalleryPeer::RANK);
    $this->photos = sfPhotoGalleryPeer::doSelect($c);
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $photo = new sfPhotoGallery();
    }
    else
    {
      $photo = sfPhotoGalleryPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($photo);
    }

    $photo->setId($this->getRequestParameter('id'));
    $photo->setEntity($this->getRequestParameter('entity'));
    $photo->setEntityId($this->getRequestParameter('entity_id'));
    $photo->setName($this->getRequestParameter('name'));
    $photo->setTitle($this->getRequestParameter('title'));
    $photo->setDescription($this->getRequestParameter('description'));

    $photo->save();

    return $this->redirect('sfPhotoGallery/show?id='.$photo->getId());
  }

  public function executeInsert()
  {

    // Qui ci arrivo solo se ho passato il validate
    // Quindi non devo fare controlli ...
    $file_name=$this->getRequest()->getFileName('file');
    $file_info=$this->getRequest()->getFile('file');
    $type=$file_info['type'];
    
    // Trova il suffisso (jpg,gif,png)
    if (eregi('gif',$type)) $suffix='gif';
    if (eregi('jpg|jpeg',$type)) $suffix='jpg';
    if (eregi('png',$type)) $suffix='png';


    // Operazioni per il DB
    $photo = new sfPhotoGallery();
    $photo->setEntity($this->getRequestParameter('entity'));
    $photo->setEntityId($this->getRequestParameter('entity_id'));
    $photo->setName($file_name);
    $photo->setMimeType($type);
    $photo->setSize($file_info['size']);
    $photo->setSuffix($suffix);
    $photo->setTitle($this->getRequestParameter('title'));
    $photo->setDescription($this->getRequestParameter('description'));
    $photo->save();

    //Creazione della thumb
    $thumbnail = new sfThumbnail(90,90);
    $thumbnail->loadFile($this->getRequest()->getFilePath('file'));
    $thumbnail->save(sfConfig::get('app_sfPhotoGalleryPlugin_dir',SF_PHOTO_GALLERY_PLUGIN_DIR).'/thumbnails/'.$photo->getRealName(), 'image/png');
    
    //Operazioni per salvare il file in upload:
    $this->getRequest()->moveFile('file', sfConfig::get('app_sfPhotoGalleryPlugin_dir',SF_PHOTO_GALLERY_PLUGIN_DIR).'/'.$photo->getRealName());

    // Fai il redirect dal referrer (TODO: oppure chiuditi come ajax)
    return $this->redirect($this->getRequestParameter('referer'));
  }

  public function executeDelete()
  {

    $photo = sfPhotoGalleryPeer::retrieveByPk($this->getRequestParameter('photo_id'));

    $this->forward404Unless($photo);

    // Remeber entity
    $entity=$photo->getEntity();
    $entity_id=$photo->getEntityId();
    // Rimuovi i file foto e thumb
    unlink($photo->getImagePath());
    unlink($photo->getThumbPath());
    
    $photo->delete();

    return $this->redirect('sfPhotoGallery/manageGallery?entity='.$entity.'&entity_id='.$entity_id);
  }
  
  public function executeUp() {

    $photo = sfPhotoGalleryPeer::retrieveByPk($this->getRequestParameter('photo_id'));
    
    $this->forward404Unless($photo);
    
    // Remeber entity
    $photo->moveUp();
    $this->redirect('sfPhotoGallery/manageGallery?entity='.$photo->getEntity().'&entity_id='.$photo->getEntityId());

  }

  public function executeDown() {

    $photo = sfPhotoGalleryPeer::retrieveByPk($this->getRequestParameter('photo_id'));
    
    $this->forward404Unless($photo);
    
    // Remeber entity
    $photo->moveDown();
    $this->redirect('sfPhotoGallery/manageGallery?entity='.$photo->getEntity().'&entity_id='.$photo->getEntityId());

  }

}
