<?php class FluidCache_renderable_TYPO3_Form_SingleSelectDropdown_ce286ba418b481420d7010348deb163bf3dc7f32 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return 'TYPO3.Form:Field';
}
public function hasLayout() {
return TRUE;
}

/**
 * section field
 */
public function section_2da0b68df8841752bb747a76780679bcd87c6215(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<div class="select">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper
$arguments1 = array();
$arguments1['property'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);
$arguments1['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments1['options'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.options', $renderingContext);
$arguments1['class'] = '';
$arguments1['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['name'] = NULL;
$arguments1['value'] = NULL;
$arguments1['dir'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$arguments1['multiple'] = NULL;
$arguments1['size'] = NULL;
$arguments1['disabled'] = NULL;
$arguments1['optionValueField'] = NULL;
$arguments1['optionLabelField'] = NULL;
$arguments1['sortByOptionLabel'] = false;
$arguments1['selectAllByDefault'] = false;
$arguments1['translate'] = NULL;
$arguments1['prependOptionLabel'] = NULL;
$arguments1['prependOptionValue'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '
	</div>
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output4 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments5 = array();
$arguments5['name'] = 'TYPO3.Form:Field';
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper7 = $self->getViewHelper('$viewHelper7', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper7->setArguments($arguments5);
$viewHelper7->setRenderingContext($renderingContext);
$viewHelper7->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output4 .= $viewHelper7->initializeArgumentsAndRender();

$output4 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments8 = array();
$arguments8['name'] = 'field';
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
$output10 = '';

$output10 .= '
	<div class="select">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper
$arguments11 = array();
$arguments11['property'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);
$arguments11['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments11['options'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.options', $renderingContext);
$arguments11['class'] = '';
$arguments11['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments11['additionalAttributes'] = NULL;
$arguments11['data'] = NULL;
$arguments11['name'] = NULL;
$arguments11['value'] = NULL;
$arguments11['dir'] = NULL;
$arguments11['lang'] = NULL;
$arguments11['style'] = NULL;
$arguments11['title'] = NULL;
$arguments11['accesskey'] = NULL;
$arguments11['tabindex'] = NULL;
$arguments11['onclick'] = NULL;
$arguments11['multiple'] = NULL;
$arguments11['size'] = NULL;
$arguments11['disabled'] = NULL;
$arguments11['optionValueField'] = NULL;
$arguments11['optionLabelField'] = NULL;
$arguments11['sortByOptionLabel'] = false;
$arguments11['selectAllByDefault'] = false;
$arguments11['translate'] = NULL;
$arguments11['prependOptionLabel'] = NULL;
$arguments11['prependOptionValue'] = NULL;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper

$output10 .= $viewHelper13->initializeArgumentsAndRender();

$output10 .= '
	</div>
';
return $output10;
};

$output4 .= '';

return $output4;
}


}
#0             6230      