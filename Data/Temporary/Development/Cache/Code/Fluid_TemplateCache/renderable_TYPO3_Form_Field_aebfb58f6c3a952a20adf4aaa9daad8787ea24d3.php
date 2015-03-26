<?php class FluidCache_renderable_TYPO3_Form_Field_aebfb58f6c3a952a20adf4aaa9daad8787ea24d3 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper
$arguments1 = array();
$arguments1['for'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.identifier', $renderingContext);
$arguments1['as'] = 'validationResults';
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
	<div class="form-group';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments4 = array();
// Rendering Boolean node
$arguments4['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'validationResults.flattenedErrors', $renderingContext));
$arguments4['then'] = ' has-error';
$arguments4['else'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper6->initializeArgumentsAndRender();

$output3 .= '"';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments7 = array();
// Rendering Boolean node
$arguments7['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.rootForm.renderingOptions.previewMode', $renderingContext));
$arguments7['then'] = NULL;
$arguments7['else'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
$output9 = '';

$output9 .= ' data-element="';
// Rendering ViewHelper TYPO3\Form\ViewHelpers\Form\FormElementRootlinePathViewHelper
$arguments10 = array();
$arguments10['renderable'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element', $renderingContext);
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper12 = $self->getViewHelper('$viewHelper12', $renderingContext, 'TYPO3\Form\ViewHelpers\Form\FormElementRootlinePathViewHelper');
$viewHelper12->setArguments($arguments10);
$viewHelper12->setRenderingContext($renderingContext);
$viewHelper12->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Form\ViewHelpers\Form\FormElementRootlinePathViewHelper

$output9 .= $viewHelper12->initializeArgumentsAndRender();

$output9 .= '"';
return $output9;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper13->setArguments($arguments7);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper13->initializeArgumentsAndRender();

$output3 .= '>
		<label class="control-label" for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments14 = array();
$arguments14['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.uniqueIdentifier', $renderingContext);
$arguments14['keepQuotes'] = false;
$arguments14['encoding'] = 'UTF-8';
$arguments14['doubleEncode'] = true;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return NULL;
};
$value16 = ($arguments14['value'] !== NULL ? $arguments14['value'] : $renderChildrenClosure15());

$output3 .= !is_string($value16) && !(is_object($value16) && method_exists($value16, '__toString')) ? $value16 : htmlspecialchars($value16, ($arguments14['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments14['encoding'], $arguments14['doubleEncode']);

$output3 .= '">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper
$arguments17 = array();
$arguments17['value'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments19 = array();
$arguments19['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.label', $renderingContext);
$arguments19['keepQuotes'] = false;
$arguments19['encoding'] = 'UTF-8';
$arguments19['doubleEncode'] = true;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return NULL;
};
$value21 = ($arguments19['value'] !== NULL ? $arguments19['value'] : $renderChildrenClosure20());
return !is_string($value21) && !(is_object($value21) && method_exists($value21, '__toString')) ? $value21 : htmlspecialchars($value21, ($arguments19['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments19['encoding'], $arguments19['doubleEncode']);
};

$output3 .= TYPO3\Fluid\ViewHelpers\Format\Nl2brViewHelper::renderStatic($arguments17, $renderChildrenClosure18, $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments22 = array();
// Rendering Boolean node
$arguments22['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.required', $renderingContext));
$arguments22['then'] = NULL;
$arguments22['else'] = NULL;
$renderChildrenClosure23 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments24 = array();
$arguments24['partial'] = 'TYPO3.Form:Field/Required';
$arguments24['section'] = NULL;
$arguments24['arguments'] = array (
);
$arguments24['optional'] = false;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper26->setArguments($arguments24);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
return $viewHelper26->initializeArgumentsAndRender();
};
$viewHelper27 = $self->getViewHelper('$viewHelper27', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper27->setArguments($arguments22);
$viewHelper27->setRenderingContext($renderingContext);
$viewHelper27->setRenderChildrenClosure($renderChildrenClosure23);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper27->initializeArgumentsAndRender();

$output3 .= '</label>
		<div class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments28 = array();
$arguments28['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.containerClassAttribute', $renderingContext);
$arguments28['keepQuotes'] = false;
$arguments28['encoding'] = 'UTF-8';
$arguments28['doubleEncode'] = true;
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$value30 = ($arguments28['value'] !== NULL ? $arguments28['value'] : $renderChildrenClosure29());

$output3 .= !is_string($value30) && !(is_object($value30) && method_exists($value30, '__toString')) ? $value30 : htmlspecialchars($value30, ($arguments28['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments28['encoding'], $arguments28['doubleEncode']);

$output3 .= '">
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments31 = array();
$arguments31['section'] = 'field';
$arguments31['partial'] = NULL;
$arguments31['arguments'] = array (
);
$arguments31['optional'] = false;
$renderChildrenClosure32 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper33 = $self->getViewHelper('$viewHelper33', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper33->setArguments($arguments31);
$viewHelper33->setRenderingContext($renderingContext);
$viewHelper33->setRenderChildrenClosure($renderChildrenClosure32);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output3 .= $viewHelper33->initializeArgumentsAndRender();

$output3 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments34 = array();
// Rendering Boolean node
$arguments34['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'validationResults.flattenedErrors', $renderingContext));
$arguments34['then'] = NULL;
$arguments34['else'] = NULL;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
$output36 = '';

$output36 .= '
				<span class="help-inline">
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments37 = array();
$arguments37['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'validationResults.errors', $renderingContext);
$arguments37['as'] = 'error';
$arguments37['key'] = '';
$arguments37['reverse'] = false;
$arguments37['iteration'] = NULL;
$renderChildrenClosure38 = function() use ($renderingContext, $self) {
$output39 = '';

$output39 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\TranslateViewHelper
$arguments40 = array();
$arguments40['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error.code', $renderingContext);
$arguments40['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error.arguments', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments41 = array();
$arguments41['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.renderingOptions.translationPackage', $renderingContext);
$arguments41['keepQuotes'] = false;
$arguments41['encoding'] = 'UTF-8';
$arguments41['doubleEncode'] = true;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$value43 = ($arguments41['value'] !== NULL ? $arguments41['value'] : $renderChildrenClosure42());
$arguments40['package'] = !is_string($value43) && !(is_object($value43) && method_exists($value43, '__toString')) ? $value43 : htmlspecialchars($value43, ($arguments41['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments41['encoding'], $arguments41['doubleEncode']);
$arguments40['source'] = 'ValidationErrors';
$arguments40['value'] = NULL;
$arguments40['quantity'] = NULL;
$arguments40['locale'] = NULL;
$renderChildrenClosure44 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments45 = array();
$arguments45['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error', $renderingContext);
$arguments45['keepQuotes'] = false;
$arguments45['encoding'] = 'UTF-8';
$arguments45['doubleEncode'] = true;
$renderChildrenClosure46 = function() use ($renderingContext, $self) {
return NULL;
};
$value47 = ($arguments45['value'] !== NULL ? $arguments45['value'] : $renderChildrenClosure46());
return !is_string($value47) && !(is_object($value47) && method_exists($value47, '__toString')) ? $value47 : htmlspecialchars($value47, ($arguments45['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments45['encoding'], $arguments45['doubleEncode']);
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Fluid\ViewHelpers\TranslateViewHelper');
$viewHelper48->setArguments($arguments40);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure44);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\TranslateViewHelper

$output39 .= $viewHelper48->initializeArgumentsAndRender();

$output39 .= '
						<br />
					';
return $output39;
};

$output36 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments37, $renderChildrenClosure38, $renderingContext);

$output36 .= '
				</span>
			';
return $output36;
};
$viewHelper49 = $self->getViewHelper('$viewHelper49', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper49->setArguments($arguments34);
$viewHelper49->setRenderingContext($renderingContext);
$viewHelper49->setRenderChildrenClosure($renderChildrenClosure35);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper49->initializeArgumentsAndRender();

$output3 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments50 = array();
// Rendering Boolean node
$arguments50['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementDescription', $renderingContext));
$arguments50['then'] = NULL;
$arguments50['else'] = NULL;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
$output52 = '';

$output52 .= '
				<span class="help-block">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments53 = array();
$arguments53['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'element.properties.elementDescription', $renderingContext);
$arguments53['keepQuotes'] = false;
$arguments53['encoding'] = 'UTF-8';
$arguments53['doubleEncode'] = true;
$renderChildrenClosure54 = function() use ($renderingContext, $self) {
return NULL;
};
$value55 = ($arguments53['value'] !== NULL ? $arguments53['value'] : $renderChildrenClosure54());

$output52 .= !is_string($value55) && !(is_object($value55) && method_exists($value55, '__toString')) ? $value55 : htmlspecialchars($value55, ($arguments53['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments53['encoding'], $arguments53['doubleEncode']);

$output52 .= '</span>
			';
return $output52;
};
$viewHelper56 = $self->getViewHelper('$viewHelper56', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper56->setArguments($arguments50);
$viewHelper56->setRenderingContext($renderingContext);
$viewHelper56->setRenderChildrenClosure($renderChildrenClosure51);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper56->initializeArgumentsAndRender();

$output3 .= '
		</div>
	</div>
';
return $output3;
};
$viewHelper57 = $self->getViewHelper('$viewHelper57', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper');
$viewHelper57->setArguments($arguments1);
$viewHelper57->setRenderingContext($renderingContext);
$viewHelper57->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper

$output0 .= $viewHelper57->initializeArgumentsAndRender();

return $output0;
}


}
#0             16270     