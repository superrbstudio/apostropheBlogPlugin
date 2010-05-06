<?php // By Default, we just want the single slot to use the same markup as the multiple-slot ?>
<?php // But by having it pull that slot in as a partial, we have the flexibility of overriding either template separately at the project level ?>
<?php include_partial('aBlog/'.$aBlogPost['template'].'_slot', array('aBlogPost' => $aBlogPost, 'options' => $options)) ?>