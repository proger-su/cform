<form action="/wp-admin/admin-ajax.php?action=add_contact" method="post" class="comment-form cform">
	<input type="hidden" name="<?php echo $this->slug; ?>_field_nonce" value="<?php echo wp_create_nonce($this->path); ?>" />
	
	<p class="comment-form-name">
		<label for="name">Name <span class="required">*</span></label>
		<input id="name" name="name" type="text" value="" size="30" maxlength="245" required="required">
	</p>
	<p class="comment-form-email">
		<label for="email">Email <span class="required">*</span></label>
		<input id="email" name="email" type="email" required="required">
	</p>
	<p class="comment-form-phone">
		<label for="phone">Phone <span class="required">*</span></label>
		<input id="phone" name="phone" type="tel" required="required">
	</p>
	<p class="form-submit">
		<input name="submit" type="submit" id="submit" class="submit" value="Submit">
	</p>
</form>