<?php
// auto-generated by sfPropelCrud
// date: 2008/04/21 22:32:09
?>
<table>
<tbody>
<tr>
<th>Id: </th>
<td><?php echo $photo->getId() ?></td>
</tr>
<tr>
<th>Entity: </th>
<td><?php echo $photo->getEntity() ?></td>
</tr>
<tr>
<th>Entity: </th>
<td><?php echo $photo->getEntityId() ?></td>
</tr>
<tr>
<th>Name: </th>
<td><?php echo $photo->getName() ?></td>
</tr>
<tr>
<th>Title: </th>
<td><?php echo $photo->getTitle() ?></td>
</tr>
<tr>
<th>Description: </th>
<td><?php echo $photo->getDescription() ?></td>
</tr>
</tbody>
</table>
<hr />
<?php echo link_to('edit', 'photo/edit?id='.$photo->getId()) ?>
&nbsp;<?php echo link_to('list', 'photo/list') ?>
