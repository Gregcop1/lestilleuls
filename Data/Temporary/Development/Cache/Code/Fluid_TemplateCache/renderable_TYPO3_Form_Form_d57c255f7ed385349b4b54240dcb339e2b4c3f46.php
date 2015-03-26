<?php class FluidCache_renderable_TYPO3_Form_Form_d57c255f7ed385349b4b54240dcb339e2b4c3f46 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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

<h1>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments1 = array();
$arguments1['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.currentPage.renderingOptions.header', $renderingContext);
$arguments1['keepQuotes'] = false;
$arguments1['encoding'] = 'UTF-8';
$arguments1['doubleEncode'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$value3 = ($arguments1['value'] !== NULL ? $arguments1['value'] : $renderChildrenClosure2());

$output0 .= !is_string($value3) && !(is_object($value3) && method_exists($value3, '__toString')) ? $value3 : htmlspecialchars($value3, ($arguments1['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments1['encoding'], $arguments1['doubleEncode']);

$output0 .= '</h1>

<div class="row t3-module-container indented">
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments4 = array();
$arguments4['class'] = 'alert alert-error';
$arguments4['as'] = 'errors';
$arguments4['additionalAttributes'] = NULL;
$arguments4['data'] = NULL;
$arguments4['severity'] = NULL;
$arguments4['dir'] = NULL;
$arguments4['id'] = NULL;
$arguments4['lang'] = NULL;
$arguments4['style'] = NULL;
$arguments4['title'] = NULL;
$arguments4['accesskey'] = NULL;
$arguments4['tabindex'] = NULL;
$arguments4['onclick'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments7 = array();
$arguments7['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errors', $renderingContext);
$arguments7['as'] = 'error';
$arguments7['key'] = '';
$arguments7['reverse'] = false;
$arguments7['iteration'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
$output9 = '';

$output9 .= '
			<div class="alert alert-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments10 = array();
$arguments10['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error.severity', $renderingContext);
$arguments10['keepQuotes'] = false;
$arguments10['encoding'] = 'UTF-8';
$arguments10['doubleEncode'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$value12 = ($arguments10['value'] !== NULL ? $arguments10['value'] : $renderChildrenClosure11());

$output9 .= !is_string($value12) && !(is_object($value12) && method_exists($value12, '__toString')) ? $value12 : htmlspecialchars($value12, ($arguments10['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments10['encoding'], $arguments10['doubleEncode']);

$output9 .= '">
				<a class="close" data-dismiss="alert">×</a>
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments13 = array();
// Rendering Boolean node
$arguments13['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('{error.title');
$arguments13['then'] = NULL;
$arguments13['else'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
					<h4 class="alert-heading">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments16 = array();
$arguments16['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error.title', $renderingContext);
$arguments16['keepQuotes'] = false;
$arguments16['encoding'] = 'UTF-8';
$arguments16['doubleEncode'] = true;
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return NULL;
};
$value18 = ($arguments16['value'] !== NULL ? $arguments16['value'] : $renderChildrenClosure17());

$output15 .= !is_string($value18) && !(is_object($value18) && method_exists($value18, '__toString')) ? $value18 : htmlspecialchars($value18, ($arguments16['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments16['encoding'], $arguments16['doubleEncode']);

$output15 .= '</h4>
				';
return $output15;
};
$viewHelper19 = $self->getViewHelper('$viewHelper19', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper19->setArguments($arguments13);
$viewHelper19->setRenderingContext($renderingContext);
$viewHelper19->setRenderChildrenClosure($renderChildrenClosure14);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output9 .= $viewHelper19->initializeArgumentsAndRender();

$output9 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments20 = array();
$arguments20['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'error', $renderingContext);
$arguments20['keepQuotes'] = false;
$arguments20['encoding'] = 'UTF-8';
$arguments20['doubleEncode'] = true;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
return NULL;
};
$value22 = ($arguments20['value'] !== NULL ? $arguments20['value'] : $renderChildrenClosure21());

$output9 .= !is_string($value22) && !(is_object($value22) && method_exists($value22, '__toString')) ? $value22 : htmlspecialchars($value22, ($arguments20['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments20['encoding'], $arguments20['doubleEncode']);

$output9 .= '
			</div>
		';
return $output9;
};

$output6 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments7, $renderChildrenClosure8, $renderingContext);

$output6 .= '
	';
return $output6;
};
$viewHelper23 = $self->getViewHelper('$viewHelper23', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper23->setArguments($arguments4);
$viewHelper23->setRenderingContext($renderingContext);
$viewHelper23->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

$output0 .= $viewHelper23->initializeArgumentsAndRender();

$output0 .= '
</div>

';
// Rendering ViewHelper TYPO3\Form\ViewHelpers\FormViewHelper
$arguments24 = array();
$arguments24['action'] = 'index';
$arguments24['object'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form', $renderingContext);
$arguments24['method'] = 'post';
$arguments24['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.identifier', $renderingContext);
$arguments24['enctype'] = 'multipart/form-data';
$arguments24['class'] = 'form-element';
$arguments24['additionalAttributes'] = NULL;
$arguments24['data'] = NULL;
$arguments24['arguments'] = array (
);
$arguments24['controller'] = NULL;
$arguments24['package'] = NULL;
$arguments24['subpackage'] = NULL;
$arguments24['section'] = '';
$arguments24['format'] = '';
$arguments24['additionalParams'] = array (
);
$arguments24['absolute'] = false;
$arguments24['addQueryString'] = false;
$arguments24['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments24['fieldNamePrefix'] = NULL;
$arguments24['actionUri'] = NULL;
$arguments24['objectName'] = NULL;
$arguments24['useParentRequest'] = false;
$arguments24['name'] = NULL;
$arguments24['onreset'] = NULL;
$arguments24['onsubmit'] = NULL;
$arguments24['dir'] = NULL;
$arguments24['lang'] = NULL;
$arguments24['style'] = NULL;
$arguments24['title'] = NULL;
$arguments24['accesskey'] = NULL;
$arguments24['tabindex'] = NULL;
$arguments24['onclick'] = NULL;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
$output26 = '';

$output26 .= '
	';
// Rendering ViewHelper TYPO3\Form\ViewHelpers\RenderRenderableViewHelper
$arguments27 = array();
$arguments27['renderable'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.currentPage', $renderingContext);
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper29 = $self->getViewHelper('$viewHelper29', $renderingContext, 'TYPO3\Form\ViewHelpers\RenderRenderableViewHelper');
$viewHelper29->setArguments($arguments27);
$viewHelper29->setRenderingContext($renderingContext);
$viewHelper29->setRenderChildrenClosure($renderChildrenClosure28);
// End of ViewHelper TYPO3\Form\ViewHelpers\RenderRenderableViewHelper

$output26 .= $viewHelper29->initializeArgumentsAndRender();

$output26 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments30 = array();
// Rendering Boolean node
$arguments30['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.finalStep', $renderingContext), \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'false', $renderingContext));
$arguments30['then'] = NULL;
$arguments30['else'] = NULL;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
$output32 = '';

$output32 .= '
		<div class="form-footer">
			<div class="container">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments33 = array();
// Rendering Boolean node
$arguments33['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.previousStepUri', $renderingContext));
$arguments33['then'] = NULL;
$arguments33['else'] = NULL;
$renderChildrenClosure34 = function() use ($renderingContext, $self) {
$output35 = '';

$output35 .= '
					<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments36 = array();
$arguments36['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.previousStepUri', $renderingContext);
$arguments36['keepQuotes'] = false;
$arguments36['encoding'] = 'UTF-8';
$arguments36['doubleEncode'] = true;
$renderChildrenClosure37 = function() use ($renderingContext, $self) {
return NULL;
};
$value38 = ($arguments36['value'] !== NULL ? $arguments36['value'] : $renderChildrenClosure37());

$output35 .= !is_string($value38) && !(is_object($value38) && method_exists($value38, '__toString')) ? $value38 : htmlspecialchars($value38, ($arguments36['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments36['encoding'], $arguments36['doubleEncode']);

$output35 .= '" class="btn pull-left">
						<i class="glyphicon glyphicon-chevron-left"></i> Back
					</a>
				';
return $output35;
};
$viewHelper39 = $self->getViewHelper('$viewHelper39', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper39->setArguments($arguments33);
$viewHelper39->setRenderingContext($renderingContext);
$viewHelper39->setRenderChildrenClosure($renderChildrenClosure34);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output32 .= $viewHelper39->initializeArgumentsAndRender();

$output32 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments40 = array();
// Rendering Boolean node
$arguments40['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.nextPage', $renderingContext));
$arguments40['then'] = NULL;
$arguments40['else'] = NULL;
$renderChildrenClosure41 = function() use ($renderingContext, $self) {
$output42 = '';

$output42 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments43 = array();
$renderChildrenClosure44 = function() use ($renderingContext, $self) {
$output45 = '';

$output45 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments46 = array();
$arguments46['name'] = '__currentPage';
$arguments46['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.nextPage.index', $renderingContext);
$arguments46['class'] = 'btn btn-primary pull-right';
$arguments46['additionalAttributes'] = NULL;
$arguments46['data'] = NULL;
$arguments46['type'] = 'submit';
$arguments46['property'] = NULL;
$arguments46['autofocus'] = NULL;
$arguments46['disabled'] = NULL;
$arguments46['form'] = NULL;
$arguments46['formaction'] = NULL;
$arguments46['formenctype'] = NULL;
$arguments46['formmethod'] = NULL;
$arguments46['formnovalidate'] = NULL;
$arguments46['formtarget'] = NULL;
$arguments46['dir'] = NULL;
$arguments46['id'] = NULL;
$arguments46['lang'] = NULL;
$arguments46['style'] = NULL;
$arguments46['title'] = NULL;
$arguments46['accesskey'] = NULL;
$arguments46['tabindex'] = NULL;
$arguments46['onclick'] = NULL;
$renderChildrenClosure47 = function() use ($renderingContext, $self) {
return '
							Next <i class="glyphicon glyphicon-chevron-right"></i>
						';
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper48->setArguments($arguments46);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure47);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output45 .= $viewHelper48->initializeArgumentsAndRender();

$output45 .= '
					';
return $output45;
};
$viewHelper49 = $self->getViewHelper('$viewHelper49', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper49->setArguments($arguments43);
$viewHelper49->setRenderingContext($renderingContext);
$viewHelper49->setRenderChildrenClosure($renderChildrenClosure44);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output42 .= $viewHelper49->initializeArgumentsAndRender();

$output42 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments50 = array();
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
$output52 = '';

$output52 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments53 = array();
$arguments53['name'] = '__currentPage';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments54 = array();
$arguments54['subject'] = NULL;
$renderChildrenClosure55 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.pages', $renderingContext);
};
$viewHelper56 = $self->getViewHelper('$viewHelper56', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper56->setArguments($arguments54);
$viewHelper56->setRenderingContext($renderingContext);
$viewHelper56->setRenderChildrenClosure($renderChildrenClosure55);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments53['value'] = $viewHelper56->initializeArgumentsAndRender();
$arguments53['class'] = 'btn btn-primary pull-right';
$arguments53['additionalAttributes'] = NULL;
$arguments53['data'] = NULL;
$arguments53['type'] = 'submit';
$arguments53['property'] = NULL;
$arguments53['autofocus'] = NULL;
$arguments53['disabled'] = NULL;
$arguments53['form'] = NULL;
$arguments53['formaction'] = NULL;
$arguments53['formenctype'] = NULL;
$arguments53['formmethod'] = NULL;
$arguments53['formnovalidate'] = NULL;
$arguments53['formtarget'] = NULL;
$arguments53['dir'] = NULL;
$arguments53['id'] = NULL;
$arguments53['lang'] = NULL;
$arguments53['style'] = NULL;
$arguments53['title'] = NULL;
$arguments53['accesskey'] = NULL;
$arguments53['tabindex'] = NULL;
$arguments53['onclick'] = NULL;
$renderChildrenClosure57 = function() use ($renderingContext, $self) {
return '
							Next <i class="glyphicon glyphicon-chevron-right"></i>
						';
};
$viewHelper58 = $self->getViewHelper('$viewHelper58', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper58->setArguments($arguments53);
$viewHelper58->setRenderingContext($renderingContext);
$viewHelper58->setRenderChildrenClosure($renderChildrenClosure57);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output52 .= $viewHelper58->initializeArgumentsAndRender();

$output52 .= '
					';
return $output52;
};
$viewHelper59 = $self->getViewHelper('$viewHelper59', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper59->setArguments($arguments50);
$viewHelper59->setRenderingContext($renderingContext);
$viewHelper59->setRenderChildrenClosure($renderChildrenClosure51);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output42 .= $viewHelper59->initializeArgumentsAndRender();

$output42 .= '
				';
return $output42;
};
$arguments40['__thenClosure'] = function() use ($renderingContext, $self) {
$output60 = '';

$output60 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments61 = array();
$arguments61['name'] = '__currentPage';
$arguments61['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.nextPage.index', $renderingContext);
$arguments61['class'] = 'btn btn-primary pull-right';
$arguments61['additionalAttributes'] = NULL;
$arguments61['data'] = NULL;
$arguments61['type'] = 'submit';
$arguments61['property'] = NULL;
$arguments61['autofocus'] = NULL;
$arguments61['disabled'] = NULL;
$arguments61['form'] = NULL;
$arguments61['formaction'] = NULL;
$arguments61['formenctype'] = NULL;
$arguments61['formmethod'] = NULL;
$arguments61['formnovalidate'] = NULL;
$arguments61['formtarget'] = NULL;
$arguments61['dir'] = NULL;
$arguments61['id'] = NULL;
$arguments61['lang'] = NULL;
$arguments61['style'] = NULL;
$arguments61['title'] = NULL;
$arguments61['accesskey'] = NULL;
$arguments61['tabindex'] = NULL;
$arguments61['onclick'] = NULL;
$renderChildrenClosure62 = function() use ($renderingContext, $self) {
return '
							Next <i class="glyphicon glyphicon-chevron-right"></i>
						';
};
$viewHelper63 = $self->getViewHelper('$viewHelper63', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper63->setArguments($arguments61);
$viewHelper63->setRenderingContext($renderingContext);
$viewHelper63->setRenderChildrenClosure($renderChildrenClosure62);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output60 .= $viewHelper63->initializeArgumentsAndRender();

$output60 .= '
					';
return $output60;
};
$arguments40['__elseClosure'] = function() use ($renderingContext, $self) {
$output64 = '';

$output64 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments65 = array();
$arguments65['name'] = '__currentPage';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments66 = array();
$arguments66['subject'] = NULL;
$renderChildrenClosure67 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.pages', $renderingContext);
};
$viewHelper68 = $self->getViewHelper('$viewHelper68', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper68->setArguments($arguments66);
$viewHelper68->setRenderingContext($renderingContext);
$viewHelper68->setRenderChildrenClosure($renderChildrenClosure67);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments65['value'] = $viewHelper68->initializeArgumentsAndRender();
$arguments65['class'] = 'btn btn-primary pull-right';
$arguments65['additionalAttributes'] = NULL;
$arguments65['data'] = NULL;
$arguments65['type'] = 'submit';
$arguments65['property'] = NULL;
$arguments65['autofocus'] = NULL;
$arguments65['disabled'] = NULL;
$arguments65['form'] = NULL;
$arguments65['formaction'] = NULL;
$arguments65['formenctype'] = NULL;
$arguments65['formmethod'] = NULL;
$arguments65['formnovalidate'] = NULL;
$arguments65['formtarget'] = NULL;
$arguments65['dir'] = NULL;
$arguments65['id'] = NULL;
$arguments65['lang'] = NULL;
$arguments65['style'] = NULL;
$arguments65['title'] = NULL;
$arguments65['accesskey'] = NULL;
$arguments65['tabindex'] = NULL;
$arguments65['onclick'] = NULL;
$renderChildrenClosure69 = function() use ($renderingContext, $self) {
return '
							Next <i class="glyphicon glyphicon-chevron-right"></i>
						';
};
$viewHelper70 = $self->getViewHelper('$viewHelper70', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper70->setArguments($arguments65);
$viewHelper70->setRenderingContext($renderingContext);
$viewHelper70->setRenderChildrenClosure($renderChildrenClosure69);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output64 .= $viewHelper70->initializeArgumentsAndRender();

$output64 .= '
					';
return $output64;
};
$viewHelper71 = $self->getViewHelper('$viewHelper71', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper71->setArguments($arguments40);
$viewHelper71->setRenderingContext($renderingContext);
$viewHelper71->setRenderChildrenClosure($renderChildrenClosure41);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output32 .= $viewHelper71->initializeArgumentsAndRender();

$output32 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments72 = array();
// Rendering Boolean node
$arguments72['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.nextStepUri', $renderingContext));
$arguments72['then'] = NULL;
$arguments72['else'] = NULL;
$renderChildrenClosure73 = function() use ($renderingContext, $self) {
$output74 = '';

$output74 .= '
					<a ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments75 = array();
// Rendering Boolean node
$arguments75['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.skipStepNotice', $renderingContext));
$output76 = '';

$output76 .= 'title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments77 = array();
$arguments77['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.skipStepNotice', $renderingContext);
$arguments77['keepQuotes'] = false;
$arguments77['encoding'] = 'UTF-8';
$arguments77['doubleEncode'] = true;
$renderChildrenClosure78 = function() use ($renderingContext, $self) {
return NULL;
};
$value79 = ($arguments77['value'] !== NULL ? $arguments77['value'] : $renderChildrenClosure78());

$output76 .= !is_string($value79) && !(is_object($value79) && method_exists($value79, '__toString')) ? $value79 : htmlspecialchars($value79, ($arguments77['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments77['encoding'], $arguments77['doubleEncode']);

$output76 .= '"';
$arguments75['then'] = $output76;
$arguments75['else'] = NULL;
$renderChildrenClosure80 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper81 = $self->getViewHelper('$viewHelper81', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper81->setArguments($arguments75);
$viewHelper81->setRenderingContext($renderingContext);
$viewHelper81->setRenderChildrenClosure($renderChildrenClosure80);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output74 .= $viewHelper81->initializeArgumentsAndRender();

$output74 .= ' rel="tooltip" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments82 = array();
$arguments82['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form.renderingOptions.nextStepUri', $renderingContext);
$arguments82['keepQuotes'] = false;
$arguments82['encoding'] = 'UTF-8';
$arguments82['doubleEncode'] = true;
$renderChildrenClosure83 = function() use ($renderingContext, $self) {
return NULL;
};
$value84 = ($arguments82['value'] !== NULL ? $arguments82['value'] : $renderChildrenClosure83());

$output74 .= !is_string($value84) && !(is_object($value84) && method_exists($value84, '__toString')) ? $value84 : htmlspecialchars($value84, ($arguments82['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments82['encoding'], $arguments82['doubleEncode']);

$output74 .= '" class="btn pull-right skip">
						Skip <i class="glyphicon glyphicon-share-alt"></i>
					</a>
				';
return $output74;
};
$viewHelper85 = $self->getViewHelper('$viewHelper85', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper85->setArguments($arguments72);
$viewHelper85->setRenderingContext($renderingContext);
$viewHelper85->setRenderChildrenClosure($renderChildrenClosure73);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output32 .= $viewHelper85->initializeArgumentsAndRender();

$output32 .= '
			</div>
		</div>
	';
return $output32;
};
$viewHelper86 = $self->getViewHelper('$viewHelper86', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper86->setArguments($arguments30);
$viewHelper86->setRenderingContext($renderingContext);
$viewHelper86->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output26 .= $viewHelper86->initializeArgumentsAndRender();

$output26 .= '
';
return $output26;
};
$viewHelper87 = $self->getViewHelper('$viewHelper87', $renderingContext, 'TYPO3\Form\ViewHelpers\FormViewHelper');
$viewHelper87->setArguments($arguments24);
$viewHelper87->setRenderingContext($renderingContext);
$viewHelper87->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Form\ViewHelpers\FormViewHelper

$output0 .= $viewHelper87->initializeArgumentsAndRender();

$output0 .= '

<script type="text/javascript">
	(function($) {
		$(function() {
			$(\'[rel="tooltip"]\').tooltip();
		});
	})(jQuery);
</script>
';

return $output0;
}


}
#0             27271     