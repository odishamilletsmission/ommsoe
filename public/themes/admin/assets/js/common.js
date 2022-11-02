$(function(){
	$(document).delegate('.popup', 'click', function(e) {
		e.preventDefault();

		$('#modal-popup').remove();

		var element = this;
		var editor="visual";
		$.ajax({
			url: editor,
			type: 'get',
			dataType: 'html',
			success: function(data) {
				html  = '<div id="modal-popup" class="modal">';
				html += '  <div class="modal-dialog modal-fullscreen">';
				html += '    <div class="modal-content">';
				//html += '      <div class="modal-header">';
				//html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
				//html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
				//html += '      </div>';
				html += '      <div class="modal-body">' + data + '</div>';
				html += '    </div';
				html += '  </div>';
				html += '</div>';

				$('body').append(html);
				Vvveb.Builder.init($(element).attr('href'), function() {
					//run code after page/iframe is loaded
				});
				Vvveb.Gui.init();

				$('#modal-popup').modal('show');
				
			}
		});
		
		
		
	});
})