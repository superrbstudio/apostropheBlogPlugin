<td>
  <ul class="a-admin-td-actions">
    <?php if ($a_blog_post->userHasPrivilege('edit')): ?>
      <?php echo $helper->linkToEdit($a_blog_post, array(  'params' =>   array(),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
      <li class="a-admin-action-media">
        <?php echo link_to(__('Manage media', array(), 'messages'), 'aBlogPostAdmin/media?id='.$a_blog_post->getId(), 'class=a-btn icon icon-only a-media') ?>
      </li>
      <?php echo $helper->linkToDelete($a_blog_post, array(  'params' =>   array(),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
    <?php endif ?>
  </ul>
</td>
