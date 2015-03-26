<?php class FluidCache_TYPO3_Setup_ViewHelpers_Widget_DatabaseSelector_action_index_1ba90ad7ebb311d2be94bc244dfe900e5da29a25 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return NULL;
}
public function hasLayout() {
return FALSE;
}

/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '<script>
(function($) {
	$(function() {
		var xhr,
			dbNameDropdownField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments1 = array();
$arguments1['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'dbNameDropdownFieldId', $renderingContext);
$arguments1['keepQuotes'] = false;
$arguments1['encoding'] = 'UTF-8';
$arguments1['doubleEncode'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$value3 = ($arguments1['value'] !== NULL ? $arguments1['value'] : $renderChildrenClosure2());

$output0 .= !is_string($value3) && !(is_object($value3) && method_exists($value3, '__toString')) ? $value3 : htmlspecialchars($value3, ($arguments1['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments1['encoding'], $arguments1['doubleEncode']);

$output0 .= '\'),
			dbNameTextField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments4 = array();
$arguments4['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'dbNameTextFieldId', $renderingContext);
$arguments4['keepQuotes'] = false;
$arguments4['encoding'] = 'UTF-8';
$arguments4['doubleEncode'] = true;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$value6 = ($arguments4['value'] !== NULL ? $arguments4['value'] : $renderChildrenClosure5());

$output0 .= !is_string($value6) && !(is_object($value6) && method_exists($value6, '__toString')) ? $value6 : htmlspecialchars($value6, ($arguments4['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments4['encoding'], $arguments4['doubleEncode']);

$output0 .= '\'),
			driverDropdownField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments7 = array();
$arguments7['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'driverDropdownFieldId', $renderingContext);
$arguments7['keepQuotes'] = false;
$arguments7['encoding'] = 'UTF-8';
$arguments7['doubleEncode'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$value9 = ($arguments7['value'] !== NULL ? $arguments7['value'] : $renderChildrenClosure8());

$output0 .= !is_string($value9) && !(is_object($value9) && method_exists($value9, '__toString')) ? $value9 : htmlspecialchars($value9, ($arguments7['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments7['encoding'], $arguments7['doubleEncode']);

$output0 .= '\'),
			userField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments10 = array();
$arguments10['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'userFieldId', $renderingContext);
$arguments10['keepQuotes'] = false;
$arguments10['encoding'] = 'UTF-8';
$arguments10['doubleEncode'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$value12 = ($arguments10['value'] !== NULL ? $arguments10['value'] : $renderChildrenClosure11());

$output0 .= !is_string($value12) && !(is_object($value12) && method_exists($value12, '__toString')) ? $value12 : htmlspecialchars($value12, ($arguments10['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments10['encoding'], $arguments10['doubleEncode']);

$output0 .= '\'),
			passwordField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments13 = array();
$arguments13['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'passwordFieldId', $renderingContext);
$arguments13['keepQuotes'] = false;
$arguments13['encoding'] = 'UTF-8';
$arguments13['doubleEncode'] = true;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
return NULL;
};
$value15 = ($arguments13['value'] !== NULL ? $arguments13['value'] : $renderChildrenClosure14());

$output0 .= !is_string($value15) && !(is_object($value15) && method_exists($value15, '__toString')) ? $value15 : htmlspecialchars($value15, ($arguments13['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments13['encoding'], $arguments13['doubleEncode']);

$output0 .= '\'),
			hostField = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments16 = array();
$arguments16['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'hostFieldId', $renderingContext);
$arguments16['keepQuotes'] = false;
$arguments16['encoding'] = 'UTF-8';
$arguments16['doubleEncode'] = true;
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return NULL;
};
$value18 = ($arguments16['value'] !== NULL ? $arguments16['value'] : $renderChildrenClosure17());

$output0 .= !is_string($value18) && !(is_object($value18) && method_exists($value18, '__toString')) ? $value18 : htmlspecialchars($value18, ($arguments16['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments16['encoding'], $arguments16['doubleEncode']);

$output0 .= '\'),
			statusContainer = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments19 = array();
$arguments19['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'statusContainerId', $renderingContext);
$arguments19['keepQuotes'] = false;
$arguments19['encoding'] = 'UTF-8';
$arguments19['doubleEncode'] = true;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return NULL;
};
$value21 = ($arguments19['value'] !== NULL ? $arguments19['value'] : $renderChildrenClosure20());

$output0 .= !is_string($value21) && !(is_object($value21) && method_exists($value21, '__toString')) ? $value21 : htmlspecialchars($value21, ($arguments19['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments19['encoding'], $arguments19['doubleEncode']);

$output0 .= '\'),
			metadataStatusContainer = $(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments22 = array();
$arguments22['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'metadataStatusContainerId', $renderingContext);
$arguments22['keepQuotes'] = false;
$arguments22['encoding'] = 'UTF-8';
$arguments22['doubleEncode'] = true;
$renderChildrenClosure23 = function() use ($renderingContext, $self) {
return NULL;
};
$value24 = ($arguments22['value'] !== NULL ? $arguments22['value'] : $renderChildrenClosure23());

$output0 .= !is_string($value24) && !(is_object($value24) && method_exists($value24, '__toString')) ? $value24 : htmlspecialchars($value24, ($arguments22['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments22['encoding'], $arguments22['doubleEncode']);

$output0 .= '\'),
			ajaxEndpoint = "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper
$arguments25 = array();
$arguments25['action'] = 'checkConnection';
// Rendering Boolean node
$arguments25['ajax'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Boolean node
$arguments25['includeWidgetContext'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments25['arguments'] = array (
);
$arguments25['section'] = '';
$arguments25['format'] = '';
$renderChildrenClosure26 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper27 = $self->getViewHelper('$viewHelper27', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper');
$viewHelper27->setArguments($arguments25);
$viewHelper27->setRenderingContext($renderingContext);
$viewHelper27->setRenderChildrenClosure($renderChildrenClosure26);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper

$output0 .= $viewHelper27->initializeArgumentsAndRender();

$output0 .= '",
			ajaxDatabaseMetadataEndpoint = "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper
$arguments28 = array();
$arguments28['action'] = 'getMetadata';
// Rendering Boolean node
$arguments28['ajax'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Boolean node
$arguments28['includeWidgetContext'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments28['arguments'] = array (
);
$arguments28['section'] = '';
$arguments28['format'] = '';
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper');
$viewHelper30->setArguments($arguments28);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure29);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Widget\UriViewHelper

$output0 .= $viewHelper30->initializeArgumentsAndRender();

$output0 .= '";

		/* ';

$output0 .= ' */

		var fillDatabaseSelector = function(databases) {
			dbNameDropdownField.html(\'<option value=""></option><option value="__new__">[New Database]</option>\');
			$.each(databases, function(index, databaseName) {
				dbNameDropdownField
					.append($(\'<option></option>\')
					.attr(\'value\', databaseName)
					.text(databaseName));
			});
			dbNameDropdownField.val(dbNameTextField.val());
		};

		var enableDatabaseSelector = function() {
			dbNameTextField.hide().attr(\'disabled\', true);
			dbNameDropdownField.show().attr(\'disabled\', false);
		};

		var disableDatabaseSelector = function() {
			dbNameDropdownField.hide().attr(\'disabled\', true);
			dbNameTextField.show().attr(\'disabled\', false);
		};

		var checkDatabaseSelection = function() {
			var selectedValue = dbNameDropdownField.val();
			metadataStatusContainer.hide();
			if (selectedValue === \'__new__\') {
				disableDatabaseSelector();
				dbNameTextField.focus();
				dbNameTextField.blur(function() {
					if ($(this).val() === \'\') {
						enableDatabaseSelector();
					}
				});
			} else if (selectedValue !== \'\') {
				metadataStatusContainer.show().removeClass(\'error db-success\').addClass(\'loading\').html(\'<div class="alert alert-info"><span class="glyphicon glyphicon-refresh glyphicon-spin"></span>Checking metadata...</div>\');
				$.ajax({
					url: ajaxDatabaseMetadataEndpoint,
					data: {
						driver: driverDropdownField.val(),
						user: userField.val(),
						password: passwordField.val(),
						host: hostField.val(),
						databaseName: selectedValue
					},
					dataType: \'json\',
					cache: false
				}).done(function(result) {
					metadataStatusContainer.removeClass(\'loading\');
					var alertClassName,
						iconClassName;
					switch (result.level) {
						case \'ok\':
							metadataStatusContainer.addClass(\'db-success\');
							alertClassName = \'success\';
							iconClassName = \'ok\';
							break;
						case \'notice\':
							alertClassName = \'info\';
							iconClassName = \'info-sign\';
							break;
						case \'warning\':
							alertClassName = \'warning\';
							iconClassName = \'warning-sign\';
							break;
						case \'error\':
							metadataStatusContainer.addClass(\'error\');
							alertClassName = \'error\';
							iconClassName = \'ban-circle\';
					}
					metadataStatusContainer.html(\'<div class="alert alert-\' + alertClassName + \'"><span class="glyphicon glyphicon-\' + iconClassName + \'"></span>\' + result.message + \'</div>\');
				}).error(function() {
					metadataStatusContainer.removeClass(\'loading\').addClass(\'error\').text(\'Unexpected error\');
				});
			}
		};

		var checkDatabaseConnection = function() {
			if (xhr && xhr.readyState !== 4) {
				xhr.abort();
			}
			statusContainer.removeClass(\'db-success error\').addClass(\'loading\').html(\'<div class="alert alert-info"><span class="glyphicon glyphicon-refresh glyphicon-spin"></span><span>Connecting ...</span></div>\');
			dbNameDropdownField.hide();
			metadataStatusContainer.hide();
			dbNameTextField.hide();
			xhr = $.ajax({
				url: ajaxEndpoint,
				data: {
					driver: driverDropdownField.val(),
					user: userField.val(),
					password: passwordField.val(),
					host: hostField.val()
				},
				dataType: \'json\',
				cache: false
			}).done(function(result) {
				statusContainer.removeClass(\'loading\').attr(\'title\', result.errorMessage);
				if (result.success) {
					statusContainer.addClass(\'db-success\').html(\'<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span>Connection established</div>\');
					fillDatabaseSelector(result.databases);
					enableDatabaseSelector();
					checkDatabaseSelection();
				} else {
					statusContainer.addClass(\'error\').html(\'<div class="alert alert-error"><span class="glyphicon glyphicon-ban-circle"></span><span>Could not connect to database</span></div>\');
					disableDatabaseSelector();
				}
			}).error(function() {
				statusContainer.removeClass(\'loading\').addClass(\'error\').text(\'Unexpected error\');
				disableDatabaseSelector();
			});
		};

		var detectChanges = function(selector, callback) {
			var timeout;
			selector.bind(\'input propertychange\', function() {
				if (window.event && event.type === \'propertychange\' && event.propertyName !== \'value\') {
					return;
				}
				if (xhr && xhr.readyState !== 4) {
					xhr.abort();
				}

				window.clearTimeout(timeout);
				timeout = setTimeout(function() {
					callback.apply(this);
				}, 750);
			});
		};

		/* ';

$output0 .= ' */

		detectChanges($(\'#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments31 = array();
$arguments31['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'userFieldId', $renderingContext);
$arguments31['keepQuotes'] = false;
$arguments31['encoding'] = 'UTF-8';
$arguments31['doubleEncode'] = true;
$renderChildrenClosure32 = function() use ($renderingContext, $self) {
return NULL;
};
$value33 = ($arguments31['value'] !== NULL ? $arguments31['value'] : $renderChildrenClosure32());

$output0 .= !is_string($value33) && !(is_object($value33) && method_exists($value33, '__toString')) ? $value33 : htmlspecialchars($value33, ($arguments31['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments31['encoding'], $arguments31['doubleEncode']);

$output0 .= ', #';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments34 = array();
$arguments34['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'passwordFieldId', $renderingContext);
$arguments34['keepQuotes'] = false;
$arguments34['encoding'] = 'UTF-8';
$arguments34['doubleEncode'] = true;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
return NULL;
};
$value36 = ($arguments34['value'] !== NULL ? $arguments34['value'] : $renderChildrenClosure35());

$output0 .= !is_string($value36) && !(is_object($value36) && method_exists($value36, '__toString')) ? $value36 : htmlspecialchars($value36, ($arguments34['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments34['encoding'], $arguments34['doubleEncode']);

$output0 .= ', #';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments37 = array();
$arguments37['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'hostFieldId', $renderingContext);
$arguments37['keepQuotes'] = false;
$arguments37['encoding'] = 'UTF-8';
$arguments37['doubleEncode'] = true;
$renderChildrenClosure38 = function() use ($renderingContext, $self) {
return NULL;
};
$value39 = ($arguments37['value'] !== NULL ? $arguments37['value'] : $renderChildrenClosure38());

$output0 .= !is_string($value39) && !(is_object($value39) && method_exists($value39, '__toString')) ? $value39 : htmlspecialchars($value39, ($arguments37['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments37['encoding'], $arguments37['doubleEncode']);

$output0 .= '\'), checkDatabaseConnection);
		driverDropdownField.change(function(event, target) {
			checkDatabaseConnection();
		});
		dbNameDropdownField.change(function(event, target) {
			checkDatabaseSelection();
		});
		checkDatabaseConnection();
	});
})(jQuery);
</script>';

return $output0;
}


}
#0             18122     