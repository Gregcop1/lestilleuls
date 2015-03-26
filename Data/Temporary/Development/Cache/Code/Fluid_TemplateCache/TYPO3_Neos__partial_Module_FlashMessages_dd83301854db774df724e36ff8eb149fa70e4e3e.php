<?php class FluidCache_TYPO3_Neos__partial_Module_FlashMessages_dd83301854db774df724e36ff8eb149fa70e4e3e extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments0 = array();
$arguments0['as'] = 'flashMessages';
$arguments0['additionalAttributes'] = NULL;
$arguments0['data'] = NULL;
$arguments0['severity'] = NULL;
$arguments0['class'] = NULL;
$arguments0['dir'] = NULL;
$arguments0['id'] = NULL;
$arguments0['lang'] = NULL;
$arguments0['style'] = NULL;
$arguments0['title'] = NULL;
$arguments0['accesskey'] = NULL;
$arguments0['tabindex'] = NULL;
$arguments0['onclick'] = NULL;
$renderChildrenClosure1 = function() use ($renderingContext, $self) {
$output2 = '';

$output2 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments3 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments4 = array();
$arguments4['subject'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments3['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('>', $viewHelper6->initializeArgumentsAndRender(), 0);
$arguments3['then'] = NULL;
$arguments3['else'] = NULL;
$renderChildrenClosure7 = function() use ($renderingContext, $self) {
$output8 = '';

$output8 .= '
		<ul id="neos-notifications-inline">
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments9 = array();
$arguments9['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
$arguments9['as'] = 'flashMessage';
$arguments9['key'] = '';
$arguments9['reverse'] = false;
$arguments9['iteration'] = NULL;
$renderChildrenClosure10 = function() use ($renderingContext, $self) {
$output11 = '';

$output11 .= '
				<li data-type="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\CaseViewHelper
$arguments12 = array();
$arguments12['mode'] = 'lower';
$arguments12['value'] = NULL;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments14 = array();
$arguments14['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext);
$arguments14['keepQuotes'] = false;
$arguments14['encoding'] = 'UTF-8';
$arguments14['doubleEncode'] = true;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return NULL;
};
$value16 = ($arguments14['value'] !== NULL ? $arguments14['value'] : $renderChildrenClosure15());
return !is_string($value16) && !(is_object($value16) && method_exists($value16, '__toString')) ? $value16 : htmlspecialchars($value16, ($arguments14['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments14['encoding'], $arguments14['doubleEncode']);
};
$viewHelper17 = $self->getViewHelper('$viewHelper17', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\CaseViewHelper');
$viewHelper17->setArguments($arguments12);
$viewHelper17->setRenderingContext($renderingContext);
$viewHelper17->setRenderChildrenClosure($renderChildrenClosure13);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\CaseViewHelper

$output11 .= $viewHelper17->initializeArgumentsAndRender();

$output11 .= '">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments18 = array();
$arguments18['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage', $renderingContext);
$arguments18['keepQuotes'] = false;
$arguments18['encoding'] = 'UTF-8';
$arguments18['doubleEncode'] = true;
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
return NULL;
};
$value20 = ($arguments18['value'] !== NULL ? $arguments18['value'] : $renderChildrenClosure19());

$output11 .= !is_string($value20) && !(is_object($value20) && method_exists($value20, '__toString')) ? $value20 : htmlspecialchars($value20, ($arguments18['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments18['encoding'], $arguments18['doubleEncode']);

$output11 .= '</li>
			';
return $output11;
};

$output8 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments9, $renderChildrenClosure10, $renderingContext);

$output8 .= '
		</ul>
	';
return $output8;
};
$viewHelper21 = $self->getViewHelper('$viewHelper21', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper21->setArguments($arguments3);
$viewHelper21->setRenderingContext($renderingContext);
$viewHelper21->setRenderChildrenClosure($renderChildrenClosure7);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output2 .= $viewHelper21->initializeArgumentsAndRender();

$output2 .= '
';
return $output2;
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper22->setArguments($arguments0);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure1);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

return $viewHelper22->initializeArgumentsAndRender();
}


}
#0             6346      