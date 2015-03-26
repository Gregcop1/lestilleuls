<?php class FluidCache_Standalone_partial_Backend_UserMenu_abfa7e81934cd5e78c3ecd70daf1f5d8eb1360c7 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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

$output0 .= '
<div class="neos-button-group neos-user-menu">
	<button class="neos-dropdown-toggle neos-button" data-toggle="dropdown" href="#" title="User Menu">
		<i class="icon-user"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments1 = array();
$arguments1['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'user.name.fullName', $renderingContext);
$arguments1['keepQuotes'] = false;
$arguments1['encoding'] = 'UTF-8';
$arguments1['doubleEncode'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$value3 = ($arguments1['value'] !== NULL ? $arguments1['value'] : $renderChildrenClosure2());

$output0 .= !is_string($value3) && !(is_object($value3) && method_exists($value3, '__toString')) ? $value3 : htmlspecialchars($value3, ($arguments1['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments1['encoding'], $arguments1['doubleEncode']);

$output0 .= '
	</button>
	<ul class="neos-dropdown-menu" role="menu" aria-labelledby="dLabel">
		<li>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments4 = array();
$arguments4['controller'] = 'Login';
$arguments4['action'] = 'logout';
$arguments4['title'] = 'Log out';
$arguments4['package'] = 'TYPO3.Neos';
$arguments4['additionalAttributes'] = NULL;
$arguments4['data'] = NULL;
$arguments4['arguments'] = array (
);
$arguments4['subpackage'] = NULL;
$arguments4['object'] = NULL;
$arguments4['section'] = '';
$arguments4['format'] = '';
$arguments4['additionalParams'] = array (
);
$arguments4['absolute'] = false;
$arguments4['addQueryString'] = false;
$arguments4['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments4['fieldNamePrefix'] = NULL;
$arguments4['actionUri'] = NULL;
$arguments4['objectName'] = NULL;
$arguments4['useParentRequest'] = false;
$arguments4['enctype'] = NULL;
$arguments4['method'] = NULL;
$arguments4['name'] = NULL;
$arguments4['onreset'] = NULL;
$arguments4['onsubmit'] = NULL;
$arguments4['class'] = NULL;
$arguments4['dir'] = NULL;
$arguments4['id'] = NULL;
$arguments4['lang'] = NULL;
$arguments4['style'] = NULL;
$arguments4['accesskey'] = NULL;
$arguments4['tabindex'] = NULL;
$arguments4['onclick'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments7 = array();
$arguments7['value'] = 'logout';
$arguments7['additionalAttributes'] = NULL;
$arguments7['data'] = NULL;
$arguments7['type'] = 'submit';
$arguments7['name'] = NULL;
$arguments7['property'] = NULL;
$arguments7['autofocus'] = NULL;
$arguments7['disabled'] = NULL;
$arguments7['form'] = NULL;
$arguments7['formaction'] = NULL;
$arguments7['formenctype'] = NULL;
$arguments7['formmethod'] = NULL;
$arguments7['formnovalidate'] = NULL;
$arguments7['formtarget'] = NULL;
$arguments7['class'] = NULL;
$arguments7['dir'] = NULL;
$arguments7['id'] = NULL;
$arguments7['lang'] = NULL;
$arguments7['style'] = NULL;
$arguments7['title'] = NULL;
$arguments7['accesskey'] = NULL;
$arguments7['tabindex'] = NULL;
$arguments7['onclick'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return '<i class="icon-off"></i> Log out';
};
$viewHelper9 = $self->getViewHelper('$viewHelper9', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper9->setArguments($arguments7);
$viewHelper9->setRenderingContext($renderingContext);
$viewHelper9->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output6 .= $viewHelper9->initializeArgumentsAndRender();

$output6 .= '
			';
return $output6;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper10->setArguments($arguments4);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output0 .= $viewHelper10->initializeArgumentsAndRender();

$output0 .= '
		</li>
		<li>
			';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments11 = array();
$arguments11['path'] = 'user/usersettings';
$arguments11['title'] = 'User Settings';
$arguments11['additionalAttributes'] = NULL;
$arguments11['data'] = NULL;
$arguments11['action'] = NULL;
$arguments11['arguments'] = array (
);
$arguments11['section'] = '';
$arguments11['format'] = '';
$arguments11['additionalParams'] = array (
);
$arguments11['addQueryString'] = false;
$arguments11['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments11['class'] = NULL;
$arguments11['dir'] = NULL;
$arguments11['id'] = NULL;
$arguments11['lang'] = NULL;
$arguments11['style'] = NULL;
$arguments11['accesskey'] = NULL;
$arguments11['tabindex'] = NULL;
$arguments11['onclick'] = NULL;
$arguments11['name'] = NULL;
$arguments11['rel'] = NULL;
$arguments11['rev'] = NULL;
$arguments11['target'] = NULL;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return '
				<i class="icon-wrench"></i> User Settings
			';
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output0 .= $viewHelper13->initializeArgumentsAndRender();

$output0 .= '
		</li>
	</ul>
</div>
';

return $output0;
}


}
#0             6411      