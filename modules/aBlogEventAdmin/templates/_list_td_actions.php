<td>
  <ul class="a-admin-td-actions">
    <?php if ($a_blog_event->userHasPrivilege('edit')): ?>
      <?php echo $helper->linkToEdit($a_blog_event, array(  'params' =>   array(),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
      <li class="a-admin-action-media">
        <?php echo link_to(__('Manage media', array(), 'messages'), 'aBlogEventAdmin/media?id='.$a_blog_event->getId(), 'class=a-btn icon icon-only a-media') ?>
      </li>
      <?php echo $helper->linkToDelete($a_blog_event, array(  'params' =>   array(),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
    <?php endif ?>
  </ul>
</td>
