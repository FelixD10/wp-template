/**
 * Custom Fonts Upload
 */
function bricksCustomFontsUpload() {
	var customFontUploadButtons = document.querySelectorAll('.bricks-font-variant .upload')
	var customFontMediaFrame = []

	for (var i = 0; i < customFontUploadButtons.length; i++) {
		if (!customFontUploadButtons[i].classList.contains('initialized')) {
			customFontUploadButtons[i].classList.add('initialized')
			customFontUploadButtons[i].addEventListener('click', customFontUpload)
		}
	}

	var customFontRemoveButtons = document.querySelectorAll('.bricks-font-variant .remove')

	for (var i = 0; i < customFontRemoveButtons.length; i++) {
		customFontRemoveButtons[i].addEventListener('click', function (e) {
			e.preventDefault()

			e.target.classList.add('hide')
			e.target.parentNode.querySelector('.upload').classList.remove('hide')

			e.target.parentNode.querySelector('[name=font_url]').value = ''
			e.target.parentNode.querySelector('[name=font_id]').value = ''
		})
	}

	function customFontUpload(e) {
		e.preventDefault()

		var id = e.target.id
		var mimeType = e.target.dataset.mimeType
		var extension = e.target.dataset.extension
		var attachmentId = e.target.dataset.id

		// Open existing media frame
		if (customFontMediaFrame[id]) {
			customFontMediaFrame[id].open()
			return
		}

		// Config media frame for custom fonts
		customFontMediaFrame[id] = wp.media.frames.file_frame = wp.media({
			title: e.target.dataset.title,
			library: {
				type: mimeType
			},
			multiple: false
		})

		var allSupportedFileUploadExtensions =
			_wpPluploadSettings.defaults.filters.mime_types[0].extensions

		// Restrict upload to font file type
		customFontMediaFrame[id].on('ready', function () {
			_wpPluploadSettings.defaults.filters.mime_types[0].extensions = extension
		})

		// Reset to all supported file extensions
		customFontMediaFrame[id].on('close', function () {
			_wpPluploadSettings.defaults.filters.mime_types[0].extensions =
				allSupportedFileUploadExtensions
		})

		// Set input value to select file URL
		customFontMediaFrame[id].on('insert select', function () {
			var font = customFontMediaFrame[id].state().get('selection').first().toJSON()

			e.target.parentNode.querySelector('[name=font_url]').value = font.url
			e.target.parentNode.querySelector('[name=font_id]').value = font.id

			e.target.classList.add('hide')
			e.target.parentNode.querySelector('.remove').classList.remove('hide')
		})

		// Select uploaded font
		customFontMediaFrame[id].on('open', function () {
			if (attachmentId) {
				var selection = customFontMediaFrame[id].state().get('selection')
				selection.add(wp.media.attachment(attachmentId))
			}
		})

		customFontMediaFrame[id].open()

		// Set custom upload param to restrict font file upload capability to Bricks custom fonts
		customFontMediaFrame[id].uploader.uploader.param('bricksCustomFontsUpload', true)
	}
}

function bricksCustomSaveFontFaces() {
	var postForm = document.querySelector('form#post')

	if (!postForm) {
		return
	}

	// Set to false after AJAX call is completed (font post meta saved) to submit post form
	var preventDefault = true

	postForm.addEventListener('submit', function (e) {
		if (!preventDefault) {
			return
		}

		e.preventDefault()

		var fontVariants = e.target.querySelectorAll('.bricks-font-variant')
		var fontPostMeta = {}

		for (var i = 0; i < fontVariants.length; i++) {
			var fontVariant = fontVariants[i]
			var fontWeight = fontVariant.querySelector('[name=font_weight]').value
			var fontStyle = fontVariant.querySelector('[name=font_style]').value

			var fontFaces = fontVariant.querySelectorAll('.font-face')

			for (var i2 = 0; i2 < fontFaces.length; i2++) {
				var fontFace = fontFaces[i2]
				var fontId = fontFace.querySelector('[name=font_id]').value
				var fontUrl = fontFace.querySelector('[name=font_url]').value
				var fontExtension = fontUrl.split('.').pop()

				// Add font variant to font post meta data (Key Google Fonts naming convention: '300', '300italic', '700oblique', etc.)
				if (fontId && fontExtension) {
					fontVariant = fontWeight + fontStyle

					if (!fontPostMeta.hasOwnProperty(fontVariant)) {
						fontPostMeta[fontVariant] = {}
					}

					fontPostMeta[fontVariant][fontExtension] = parseInt(fontId)
				}
			}
		}

		var postIdEl = document.getElementById('post_ID')
		var postId = postIdEl ? postIdEl.value : 0
		var submitButton = postForm.querySelector('#publishing-action input[type=submit]')

		if (!postId) {
			postForm.submit()
			return
		}

		/**
		 * Save custom font post meta
		 *
		 * Then manually trigger form submit via click, not postForm.submit() as 'Publish' action uses additional click listeners
		 */
		window.wp.ajax.send('bricks_save_font_faces', {
			data: {
				nonce: window.bricksData.nonce,
				post_id: postId,
				font_faces: JSON.stringify(fontPostMeta)
			},

			success: function (res) {
				preventDefault = false
				submitButton.classList.remove('disabled')
				submitButton.click()
			},

			error: function (res) {
				preventDefault = false
				submitButton.classList.remove('disabled')
				submitButton.click()
			}
		})
	})
}

function bricksCustomFontsAddVariant() {
	var addVariantButton = document.getElementById('bricks-custom-fonts-add-font-variant')

	if (!addVariantButton) {
		return
	}

	addVariantButton.addEventListener('click', function (e) {
		e.preventDefault()

		var fontVariants = document.querySelectorAll('.bricks-font-variant')
		var fontVariant = fontVariants[fontVariants.length - 1]
		var fontVariantClone = fontVariant ? fontVariant.cloneNode(true) : false

		if (!fontVariantClone) {
			return
		}

		// Reset font-weight & font-style
		fontVariantClone.querySelector('[name=font_weight]').selectedIndex = 3
		fontVariantClone.querySelector('[name=font_style]').selectedIndex = 0

		// Clear all font variant values
		fontVariantClone.querySelectorAll('input').forEach(function (input) {
			input.value = ''
		})

		// Remove all .initialized CSS classes
		fontVariantClone.querySelectorAll('.initialized').forEach(function (button) {
			button.classList.remove('initialized')
		})

		// Remove font preview <style>
		if (fontVariantClone.querySelector('style')) {
			fontVariantClone.querySelector('style').remove()
		}

		fontVariantClone.querySelector('.pangram').removeAttribute('style')

		fontVariantClone.querySelectorAll('button').forEach(function (button) {
			if (button.classList.contains('upload')) {
				button.classList.remove('hide')
				button.id = Math.random()
					.toString(36)
					.replace(/[^a-z]+/g, '')
					.substr(0, 12)
			} else if (button.classList.contains('remove')) {
				button.classList.add('hide')
			}
		})

		fontVariant.after(fontVariantClone)

		// Re-init listeners
		bricksCustomFontsUpload()
		bricksCustomFontsToggleEdit()
		bricksCustomFontsDeleteVariant()
	})
}

function bricksCustomFontsToggleEdit() {
	var toggleButtons = document.querySelectorAll('#bricks-font-metabox .actions .edit')

	for (var i = 0; i < toggleButtons.length; i++) {
		if (!toggleButtons[i].classList.contains('initialized')) {
			toggleButtons[i].classList.add('initialized')

			toggleButtons[i].addEventListener('click', function (e) {
				e.preventDefault()

				var labelOld = e.target.innerText
				e.target.innerText = e.target.dataset.label
				e.target.dataset.label = labelOld

				var fontFaces = e.target.closest('.bricks-font-variant').querySelector('.font-faces')
				fontFaces.classList.toggle('hide')
			})
		}
	}
}

function bricksCustomFontsDeleteVariant() {
	var deleteVariantButtons = document.querySelectorAll('#bricks-font-metabox .actions .delete')

	for (var i = 0; i < deleteVariantButtons.length; i++) {
		if (!deleteVariantButtons[i].classList.contains('initialized')) {
			deleteVariantButtons[i].classList.add('initialized')

			deleteVariantButtons[i].addEventListener('click', function (e) {
				var fontVariantWrappers = document.querySelectorAll('.bricks-font-variant')

				e.preventDefault()

				if (fontVariantWrappers.length > 1) {
					e.target.closest('.bricks-font-variant').remove()
				} else {
					var fontVariantWrapper = fontVariantWrappers[0]

					fontVariantWrapper.querySelector('[name=font_weight]').value = 400
					fontVariantWrapper.querySelector('[name=font_style]').value = ''

					var inputs = fontVariantWrapper.querySelectorAll('input')

					for (var i2 = 0; i2 < inputs.length; i2++) {
						inputs[i2].value = ''
					}

					var fontStyle = fontVariantWrapper.querySelector('style')

					if (fontStyle) {
						fontStyle.remove()
					}

					var pangram = fontVariantWrapper.querySelector('.pangram')

					if (pangram) {
						pangram.removeAttribute('style')
					}
				}
			})
		}
	}
}

document.addEventListener('DOMContentLoaded', function (e) {
	bricksCustomFontsUpload()
	bricksCustomSaveFontFaces()
	bricksCustomFontsAddVariant()
	bricksCustomFontsToggleEdit()
	bricksCustomFontsDeleteVariant()
})
