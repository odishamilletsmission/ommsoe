<?php echo $content; ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$("#complain_form").submit(function(e) {
	//alert("ok");
    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');
    
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
          alert(data); // show response from the php script.
        }
    });
    
});
});

//--></script>
<?php js_end(); ?>