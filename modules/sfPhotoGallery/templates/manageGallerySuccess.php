<?php
$has_sfIconPlugin=false;
$delete_label="DELETE";
$edit_label="EDIT";
$up_label="UP";
$down_label="DOWN";
if (file_exists(SF_ROOT_DIR.'/plugins/sfIconPlugin')) {
  $has_sfIconPlugin=true;
  use_helper('sfIcon');
  $delete_label=icon_tag('remove');
  $edit_label=icon_tag('image_edit');
  $up_label=icon_tag('page_up');
  $down_label=icon_tag('page_down');
 }
?>

<table>
<tr>
<th>Thumb</th>
<th>Title</th>
<th>Actions</th>
</tr>

<?php foreach ($photos as $photo) : ?>
<tr>
<td><?php echo image_tag($photo->getThumbName()) ?></td>
<td><?php echo $photo->getTitle() ?></td>
<td>
<?php
echo link_to($delete_label,'sfPhotoGallery/delete?photo_id='.$photo->getId(),'confirm="Sei sicuro di voler cancellare la foto?" title="Elimina la foto"');
echo link_to($edit_label,'sfPhotoGallery/editPhoto?photo_id='.$photo->getId(),'title="Modifica gli attributi della foto: tiolo e descrizione."');
if(!$photo->isFirst())
  echo link_to($up_label,'sfPhotoGallery/up?photo_id='.$photo->getId(),'title="Sposta di una posizione verso l\'alto."');
if(!$photo->isLast())
  echo link_to($down_label,'sfPhotoGallery/down?photo_id='.$photo->getId(),'title="Sposta di una posizione verso il basso"');
?>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php use_helper('Object') ?>

<h1>AGGIUNGI UNA FOTO</h1>

<?php echo form_tag('sfPhotoGallery/insert','multipart=true') ?>

<?php echo input_hidden_tag('entity',$entity) ?>
<?php echo input_hidden_tag('entity_id',$entity_id) ?>

  <?php echo input_hidden_tag('referer',$sf_request->getURI()) ?>
<table>
<tbody>

<tr>
  <th>Immagine:</th>
  <td><?php echo input_file_tag('file') ?></td>
</tr>


<tr>
  <th>Titolo della foto:</th>
  <td><?php echo input_tag('title', '' ,array (
  'size' => 30,
)) ?></td>
</tr>

<tr>
  <th>Descrizione della foto:</th>
  <td><?php echo textarea_tag('description','',array (
  'size' => '30x4',
)) ?></td>
</tr>

</tbody>
</table>

<hr />
<?php echo submit_tag('Aggiungi la foto') ?>
</form>


