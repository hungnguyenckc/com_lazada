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
<form action="<?php echo JRoute::_('index.php?option=com_lazada&view=product_redshop', false) ?>" method="post" id="adminForm" name="adminForm">
	
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
				<?php echo JHtml::_('grid.sort', 'Name', 'product_name', $listDirn, $listOrder); ?>
			</th>
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Image', 'product_full_image', $listDirn, $listOrder); ?>
			</th>				
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Price', 'product_price', $listDirn, $listOrder); ?>
			</th>					
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'COM_LAZADA_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>	
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'COM_LAZADA_ID', 'product_id', $listDirn, $listOrder); ?>
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
					$link = JRoute::_('index.php?option=com_lazadad&task=product_lazada.edit&id=' . $row->product_id);
				?>
						<tr>
							<td>
								<?php echo $this->pagination->getRowOffset($i); ?>
							</td>
							<td>
								<?php echo JHtml::_('grid.id', $i, $row->product_id); ?>
							</td>
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_NAME') ?>">
									<?php echo $row->product_name; ?>
								</a>
							</td>						
							<td>
								<?php if(filter_var($row->product_full_image, FILTER_VALIDATE_URL))	{ ?>
									<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<img src="<?= $row->product_full_image ?>">

								<?php }else { ?>
								
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<img src="../components/com_redshop/assets/images/product/<?= $row->product_full_image ?>">
								<?php } ?>

									
								</a>
							</td>							
							<td>
								<a href="" title="<?= JText::_('COM_LAZADA_EDIT_LAZADA') ?>">
									<?= $row->product_price ?> đ
								</a>
							</td>							
							<td align="center">
								<?php echo JHtml::_('jgrid.published', $row->published, $i, 'lazadas.', true, 'cb'); ?>
							</td>										
							<td align="center">
								<?php echo $row->product_id; ?>
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
