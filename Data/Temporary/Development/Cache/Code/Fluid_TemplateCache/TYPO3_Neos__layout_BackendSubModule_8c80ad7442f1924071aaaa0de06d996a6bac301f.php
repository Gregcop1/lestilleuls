<?php class FluidCache_TYPO3_Neos__layout_BackendSubModule_8c80ad7442f1924071aaaa0de06d996a6bac301f extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
<div class="neos-content neos-container-fluid">
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments1 = array();
$arguments1['section'] = 'subtitle';
// Rendering Boolean node
$arguments1['optional'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('1');
$arguments1['partial'] = NULL;
$arguments1['arguments'] = array (
);
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '

	<div class="neos-module-container">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments4 = array();
$arguments4['partial'] = 'Module/FlashMessages';
$arguments4['section'] = NULL;
$arguments4['arguments'] = array (
);
$arguments4['optional'] = false;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output0 .= $viewHelper6->initializeArgumentsAndRender();

$output0 .= '
	</div>

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper
$arguments7 = array();
$arguments7['for'] = '';
$arguments7['as'] = 'validationResults';
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
$output9 = '';

$output9 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments10 = array();
// Rendering Boolean node
$arguments10['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'validationResults.flattenedErrors', $renderingContext));
$arguments10['then'] = NULL;
$arguments10['else'] = NULL;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
$output12 = '';

$output12 .= '
			<div class="neos-row neos-module-container neos-indented">
				<div class="neos-alert neos-alert-error">
					<p><strong>The following errors occurred</strong></p>
					<ul class="neos-error">
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments13 = array();
$arguments13['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'validationResults.flattenedErrors', $renderingContext);
$arguments13['key'] = 'propertyPath';
$arguments13['as'] = 'errors';
$arguments13['reverse'] = false;
$arguments13['iteration'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
							<li>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments16 = array();
$arguments16['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'propertyPath', $renderingContext);
$arguments16['keepQuotes'] = false;
$arguments16['encoding'] = 'UTF-8';
$arguments16['doubleEncode'] = true;
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return NULL;
};
$value18 = ($arguments16['value'] !== NULL ? $arguments16['value'] : $renderChildrenClosure17());

$output15 .= !is_string($value18) && !(is_object($value18) && method_exists($value18, '__toString')) ? $value18 : htmlspecialchars($value18, ($arguments16['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments16['encoding'], $arguments16['doubleEncode']);

$output15 .= ': ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments19 = array();
$arguments19['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errors', $renderingContext);
$arguments19['as'] = 'error';
$arguments19['key'] = '';
$arguments19['reverse'] = false;
$arguments19['iteration'] = NULL;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments21 = array();
$arguments21['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error', $renderingContext);
$arguments21['keepQuotes'] = false;
$arguments21['encoding'] = 'UTF-8';
$arguments21['doubleEncode'] = true;
$renderChildrenClosure22 = function() use ($renderingContext, $self) {
return NULL;
};
$value23 = ($arguments21['value'] !== NULL ? $arguments21['value'] : $renderChildrenClosure22());
return !is_string($value23) && !(is_object($value23) && method_exists($value23, '__toString')) ? $value23 : htmlspecialchars($value23, ($arguments21['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments21['encoding'], $arguments21['doubleEncode']);
};

$output15 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments19, $renderChildrenClosure20, $renderingContext);

$output15 .= '</li>
						';
return $output15;
};

$output12 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments13, $renderChildrenClosure14, $renderingContext);

$output12 .= '
					</ul>
				</div>
			</div>
		';
return $output12;
};
$viewHelper24 = $self->getViewHelper('$viewHelper24', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper24->setArguments($arguments10);
$viewHelper24->setRenderingContext($renderingContext);
$viewHelper24->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output9 .= $viewHelper24->initializeArgumentsAndRender();

$output9 .= '
	';
return $output9;
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper');
$viewHelper25->setArguments($arguments7);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper

$output0 .= $viewHelper25->initializeArgumentsAndRender();

$output0 .= '

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments26 = array();
$arguments26['section'] = 'content';
$arguments26['partial'] = NULL;
$arguments26['arguments'] = array (
);
$arguments26['optional'] = false;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper28->setArguments($arguments26);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output0 .= $viewHelper28->initializeArgumentsAndRender();

$output0 .= '
</div>';

return $output0;
}


}
#0             8107      