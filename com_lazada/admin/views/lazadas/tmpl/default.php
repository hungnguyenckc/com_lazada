<?php
defined('_JEXEC');
JHtml::_('formbehavior.chosen','select');

$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
       var form = document.adminForm;

       if (pressbutton) {
           form.task.value = pressbutton;
       }

       try {
           form.onsubmit();
       }
       catch (e) {
       }

       form.submit();
   };
</script>
<form action="<?php echo JRoute::_('index.php?option=com_lazada&view=lazadas', false) ?>" method="post" id="adminForm" name="adminForm">
	
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10">

	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_LAZADA_LAZADAS_FILTER'); ?>
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array('view' => $this)
				);
			?>
		</div>
	</div>

	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="1%"><?php echo JText::_('COM_LAZADA_NUM'); ?></th>
			<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="50%">
				<?php echo JHtml::_('grid.sort', 'Name', 'name', $listDirn, $listOrder); ?>
			</th>
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Image', 'image', $listDirn, $listOrder); ?>
			</th>				
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Price', 'price', $listDirn, $listOrder); ?>
			</th>			
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Category', 'category', $listDirn, $listOrder); ?>
			</th>			
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'COM_LAZADA_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>	
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'COM_LAZADA_ID', 'id', $listDirn, $listOrder); ?>
			</th>	
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) : 
					$link = JRoute::_('index.php?option=com_lazadad&task=lazada.edit&id=' . $row->id);
				?>
						<tr>
							<td>
								<?php echo $this->pagination->getRowOffset($i); ?>
							</td>
							<td>
								<?php echo JHtml::_('grid.id', $i, $row->id); ?>
							</td>
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_NAME') ?>">
									<?php echo $row->name; ?>
								</a>
							</td>						
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<img src="<?= $row->image ?>">
								</a>
							</td>							
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<?= $row->price ?> đ
								</a>
							</td>							
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<?= $row->primaryCategory ?>
								</a>
							</td>	
							<td align="center">
								<?php echo JHtml::_('jgrid.published', $row->published, $i, 'lazadas.', true, 'cb'); ?>
							</td>										
							<td align="center">
								<?php echo $row->id; ?>
							</td>
						</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="boxchecked" value="0">
	<input type="hidden" name="filter_order" value="<?= $listOrder ?>">
	<input type="hidden" name="filter_order_Dir" value="<?= $listDirn ?>">
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
