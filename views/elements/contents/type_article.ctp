<?php
echo $this->Form->hidden('Content.model', array('value' => 'Flour.ContentObject'));
echo $this->Form->hidden('Content.foreign_id');

echo $this->Form->input('ContentObject.title');

