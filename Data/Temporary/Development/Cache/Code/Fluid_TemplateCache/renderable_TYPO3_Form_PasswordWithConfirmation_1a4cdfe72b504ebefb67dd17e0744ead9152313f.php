<?php class FluidCache_renderable_TYPO3_Form_PasswordWithConfirmation_1a4cdfe72b504ebefb67dd17e0744ead9152313f extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper
$arguments1 = array();
$output2 = '';

$output2 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);

$output2 .= '.password';
$arguments1['property'] = $output2;
$arguments1['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments1['class'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementClassAttribute', $renderingContext);
$arguments1['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['name'] = NULL;
$arguments1['value'] = NULL;
$arguments1['disabled'] = NULL;
$arguments1['maxlength'] = NULL;
$arguments1['readonly'] = NULL;
$arguments1['size'] = NULL;
$arguments1['placeholder'] = NULL;
$arguments1['dir'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$renderChildrenClosure3 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper4 = $self->getViewHelper('$viewHelper4', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper');
$viewHelper4->setArguments($arguments1);
$viewHelper4->setRenderingContext($renderingContext);
$viewHelper4->setRenderChildrenClosure($renderChildrenClosure3);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper

$output0 .= $viewHelper4->initializeArgumentsAndRender();

$output0 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments5 = array();
// Rendering Boolean node
$arguments5['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.passwordDescription', $renderingContext));
$arguments5['then'] = NULL;
$arguments5['else'] = NULL;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
$output7 = '';

$output7 .= '
		<span class="help-block">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments8 = array();
$arguments8['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.passwordDescription', $renderingContext);
$arguments8['keepQuotes'] = false;
$arguments8['encoding'] = 'UTF-8';
$arguments8['doubleEncode'] = true;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$value10 = ($arguments8['value'] !== NULL ? $arguments8['value'] : $renderChildrenClosure9());

$output7 .= !is_string($value10) && !(is_object($value10) && method_exists($value10, '__toString')) ? $value10 : htmlspecialchars($value10, ($arguments8['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments8['encoding'], $arguments8['doubleEncode']);

$output7 .= '</span>
	';
return $output7;
};
$viewHelper11 = $self->getViewHelper('$viewHelper11', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper11->setArguments($arguments5);
$viewHelper11->setRenderingContext($renderingContext);
$viewHelper11->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper11->initializeArgumentsAndRender();

$output0 .= '
	<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments12 = array();
$arguments12['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments12['keepQuotes'] = false;
$arguments12['encoding'] = 'UTF-8';
$arguments12['doubleEncode'] = true;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
return NULL;
};
$value14 = ($arguments12['value'] !== NULL ? $arguments12['value'] : $renderChildrenClosure13());

$output0 .= !is_string($value14) && !(is_object($value14) && method_exists($value14, '__toString')) ? $value14 : htmlspecialchars($value14, ($arguments12['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments12['encoding'], $arguments12['doubleEncode']);

$output0 .= '-confirmation" class="control-label">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper
$arguments15 = array();
$arguments15['value'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments17 = array();
$arguments17['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.confirmationLabel', $renderingContext);
$arguments17['keepQuotes'] = false;
$arguments17['encoding'] = 'UTF-8';
$arguments17['doubleEncode'] = true;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return NULL;
};
$value19 = ($arguments17['value'] !== NULL ? $arguments17['value'] : $renderChildrenClosure18());
return !is_string($value19) && !(is_object($value19) && method_exists($value19, '__toString')) ? $value19 : htmlspecialchars($value19, ($arguments17['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments17['encoding'], $arguments17['doubleEncode']);
};

$output0 .= TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper::renderStatic($arguments15, $renderChildrenClosure16, $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments20 = array();
// Rendering Boolean node
$arguments20['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.required', $renderingContext));
$arguments20['then'] = NULL;
$arguments20['else'] = NULL;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments22 = array();
$arguments22['partial'] = 'TYPO3.Form:Field/Required';
$arguments22['section'] = NULL;
$arguments22['arguments'] = array (
);
$arguments22['optional'] = false;
$renderChildrenClosure23 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper24 = $self->getViewHelper('$viewHelper24', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper24->setArguments($arguments22);
$viewHelper24->setRenderingContext($renderingContext);
$viewHelper24->setRenderChildrenClosure($renderChildrenClosure23);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
return $viewHelper24->initializeArgumentsAndRender();
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper25->setArguments($arguments20);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper25->initializeArgumentsAndRender();

$output0 .= '</label>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper
$arguments26 = array();
$output27 = '';

$output27 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);

$output27 .= '.confirmation';
$arguments26['property'] = $output27;
$output28 = '';

$output28 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);

$output28 .= '-confirmation';
$arguments26['id'] = $output28;
$arguments26['class'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.confirmationClassAttribute', $renderingContext);
$arguments26['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments26['additionalAttributes'] = NULL;
$arguments26['data'] = NULL;
$arguments26['name'] = NULL;
$arguments26['value'] = NULL;
$arguments26['disabled'] = NULL;
$arguments26['maxlength'] = NULL;
$arguments26['readonly'] = NULL;
$arguments26['size'] = NULL;
$arguments26['placeholder'] = NULL;
$arguments26['dir'] = NULL;
$arguments26['lang'] = NULL;
$arguments26['style'] = NULL;
$arguments26['title'] = NULL;
$arguments26['accesskey'] = NULL;
$arguments26['tabindex'] = NULL;
$arguments26['onclick'] = NULL;
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper');
$viewHelper30->setArguments($arguments26);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure29);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper

$output0 .= $viewHelper30->initializeArgumentsAndRender();

$output0 .= '
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output31 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments32 = array();
$arguments32['name'] = 'TYPO3.Form:Field';
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output31 .= $viewHelper34->initializeArgumentsAndRender();

$output31 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments35 = array();
$arguments35['name'] = 'field';
$renderChildrenClosure36 = function() use ($renderingContext, $self) {
$output37 = '';

$output37 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper
$arguments38 = array();
$output39 = '';

$output39 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);

$output39 .= '.password';
$arguments38['property'] = $output39;
$arguments38['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments38['class'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementClassAttribute', $renderingContext);
$arguments38['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments38['additionalAttributes'] = NULL;
$arguments38['data'] = NULL;
$arguments38['name'] = NULL;
$arguments38['value'] = NULL;
$arguments38['disabled'] = NULL;
$arguments38['maxlength'] = NULL;
$arguments38['readonly'] = NULL;
$arguments38['size'] = NULL;
$arguments38['placeholder'] = NULL;
$arguments38['dir'] = NULL;
$arguments38['lang'] = NULL;
$arguments38['style'] = NULL;
$arguments38['title'] = NULL;
$arguments38['accesskey'] = NULL;
$arguments38['tabindex'] = NULL;
$arguments38['onclick'] = NULL;
$renderChildrenClosure40 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper41 = $self->getViewHelper('$viewHelper41', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper');
$viewHelper41->setArguments($arguments38);
$viewHelper41->setRenderingContext($renderingContext);
$viewHelper41->setRenderChildrenClosure($renderChildrenClosure40);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper

$output37 .= $viewHelper41->initializeArgumentsAndRender();

$output37 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments42 = array();
// Rendering Boolean node
$arguments42['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.passwordDescription', $renderingContext));
$arguments42['then'] = NULL;
$arguments42['else'] = NULL;
$renderChildrenClosure43 = function() use ($renderingContext, $self) {
$output44 = '';

$output44 .= '
		<span class="help-block">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments45 = array();
$arguments45['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.passwordDescription', $renderingContext);
$arguments45['keepQuotes'] = false;
$arguments45['encoding'] = 'UTF-8';
$arguments45['doubleEncode'] = true;
$renderChildrenClosure46 = function() use ($renderingContext, $self) {
return NULL;
};
$value47 = ($arguments45['value'] !== NULL ? $arguments45['value'] : $renderChildrenClosure46());

$output44 .= !is_string($value47) && !(is_object($value47) && method_exists($value47, '__toString')) ? $value47 : htmlspecialchars($value47, ($arguments45['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments45['encoding'], $arguments45['doubleEncode']);

$output44 .= '</span>
	';
return $output44;
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper48->setArguments($arguments42);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure43);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output37 .= $viewHelper48->initializeArgumentsAndRender();

$output37 .= '
	<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments49 = array();
$arguments49['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments49['keepQuotes'] = false;
$arguments49['encoding'] = 'UTF-8';
$arguments49['doubleEncode'] = true;
$renderChildrenClosure50 = function() use ($renderingContext, $self) {
return NULL;
};
$value51 = ($arguments49['value'] !== NULL ? $arguments49['value'] : $renderChildrenClosure50());

$output37 .= !is_string($value51) && !(is_object($value51) && method_exists($value51, '__toString')) ? $value51 : htmlspecialchars($value51, ($arguments49['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments49['encoding'], $arguments49['doubleEncode']);

$output37 .= '-confirmation" class="control-label">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper
$arguments52 = array();
$arguments52['value'] = NULL;
$renderChildrenClosure53 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments54 = array();
$arguments54['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.confirmationLabel', $renderingContext);
$arguments54['keepQuotes'] = false;
$arguments54['encoding'] = 'UTF-8';
$arguments54['doubleEncode'] = true;
$renderChildrenClosure55 = function() use ($renderingContext, $self) {
return NULL;
};
$value56 = ($arguments54['value'] !== NULL ? $arguments54['value'] : $renderChildrenClosure55());
return !is_string($value56) && !(is_object($value56) && method_exists($value56, '__toString')) ? $value56 : htmlspecialchars($value56, ($arguments54['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments54['encoding'], $arguments54['doubleEncode']);
};

$output37 .= TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper::renderStatic($arguments52, $renderChildrenClosure53, $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments57 = array();
// Rendering Boolean node
$arguments57['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.required', $renderingContext));
$arguments57['then'] = NULL;
$arguments57['else'] = NULL;
$renderChildrenClosure58 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments59 = array();
$arguments59['partial'] = 'TYPO3.Form:Field/Required';
$arguments59['section'] = NULL;
$arguments59['arguments'] = array (
);
$arguments59['optional'] = false;
$renderChildrenClosure60 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper61 = $self->getViewHelper('$viewHelper61', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper61->setArguments($arguments59);
$viewHelper61->setRenderingContext($renderingContext);
$viewHelper61->setRenderChildrenClosure($renderChildrenClosure60);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
return $viewHelper61->initializeArgumentsAndRender();
};
$viewHelper62 = $self->getViewHelper('$viewHelper62', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper62->setArguments($arguments57);
$viewHelper62->setRenderingContext($renderingContext);
$viewHelper62->setRenderChildrenClosure($renderChildrenClosure58);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output37 .= $viewHelper62->initializeArgumentsAndRender();

$output37 .= '</label>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper
$arguments63 = array();
$output64 = '';

$output64 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);

$output64 .= '.confirmation';
$arguments63['property'] = $output64;
$output65 = '';

$output65 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);

$output65 .= '-confirmation';
$arguments63['id'] = $output65;
$arguments63['class'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.confirmationClassAttribute', $renderingContext);
$arguments63['errorClass'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementErrorClassAttribute', $renderingContext);
$arguments63['additionalAttributes'] = NULL;
$arguments63['data'] = NULL;
$arguments63['name'] = NULL;
$arguments63['value'] = NULL;
$arguments63['disabled'] = NULL;
$arguments63['maxlength'] = NULL;
$arguments63['readonly'] = NULL;
$arguments63['size'] = NULL;
$arguments63['placeholder'] = NULL;
$arguments63['dir'] = NULL;
$arguments63['lang'] = NULL;
$arguments63['style'] = NULL;
$arguments63['title'] = NULL;
$arguments63['accesskey'] = NULL;
$arguments63['tabindex'] = NULL;
$arguments63['onclick'] = NULL;
$renderChildrenClosure66 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper67 = $self->getViewHelper('$viewHelper67', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper');
$viewHelper67->setArguments($arguments63);
$viewHelper67->setRenderingContext($renderingContext);
$viewHelper67->setRenderChildrenClosure($renderChildrenClosure66);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper

$output37 .= $viewHelper67->initializeArgumentsAndRender();

$output37 .= '
';
return $output37;
};

$output31 .= '';

return $output31;
}


}
#0             21420     