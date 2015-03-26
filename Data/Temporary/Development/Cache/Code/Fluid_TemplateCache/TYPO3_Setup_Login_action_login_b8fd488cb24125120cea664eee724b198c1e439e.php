<?php class FluidCache_TYPO3_Setup_Login_action_login_b8fd488cb24125120cea664eee724b198c1e439e extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return 'Default';
}
public function hasLayout() {
return TRUE;
}

/**
 * section content
 */
public function section_040f06fd774092478d450774f5ba30c5da78acc8(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<div class="t3-module-container indented">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments1 = array();
$arguments1['action'] = 'authenticate';
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['arguments'] = array (
);
$arguments1['controller'] = NULL;
$arguments1['package'] = NULL;
$arguments1['subpackage'] = NULL;
$arguments1['object'] = NULL;
$arguments1['section'] = '';
$arguments1['format'] = '';
$arguments1['additionalParams'] = array (
);
$arguments1['absolute'] = false;
$arguments1['addQueryString'] = false;
$arguments1['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments1['fieldNamePrefix'] = NULL;
$arguments1['actionUri'] = NULL;
$arguments1['objectName'] = NULL;
$arguments1['useParentRequest'] = false;
$arguments1['enctype'] = NULL;
$arguments1['method'] = NULL;
$arguments1['name'] = NULL;
$arguments1['onreset'] = NULL;
$arguments1['onsubmit'] = NULL;
$arguments1['class'] = NULL;
$arguments1['dir'] = NULL;
$arguments1['id'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
			<h1 class="text-center">Login</h1>
			<fieldset>
				<legend class="text-center">Enter the setup password to continue:</legend>
				<div class="login-box">
					<div class="form-group">
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments4 = array();
$arguments4['class'] = 'form-control';
$arguments4['id'] = 'password';
$arguments4['type'] = 'password';
$arguments4['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][PasswordToken][password]';
// Rendering Array
$array5 = array();
$array5['autofocus'] = 'autofocus';
$arguments4['additionalAttributes'] = $array5;
$arguments4['placeholder'] = 'Password';
$arguments4['data'] = NULL;
$arguments4['required'] = false;
$arguments4['value'] = NULL;
$arguments4['property'] = NULL;
$arguments4['disabled'] = NULL;
$arguments4['maxlength'] = NULL;
$arguments4['readonly'] = NULL;
$arguments4['size'] = NULL;
$arguments4['autofocus'] = NULL;
$arguments4['errorClass'] = 'f3-form-error';
$arguments4['dir'] = NULL;
$arguments4['lang'] = NULL;
$arguments4['style'] = NULL;
$arguments4['title'] = NULL;
$arguments4['accesskey'] = NULL;
$arguments4['tabindex'] = NULL;
$arguments4['onclick'] = NULL;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper7 = $self->getViewHelper('$viewHelper7', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper7->setArguments($arguments4);
$viewHelper7->setRenderingContext($renderingContext);
$viewHelper7->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output3 .= $viewHelper7->initializeArgumentsAndRender();

$output3 .= '
					</div>
					<div class="form-group">
						<div class="controls">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper
$arguments8 = array();
$arguments8['name'] = 'step';
$arguments8['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'step', $renderingContext);
$arguments8['additionalAttributes'] = NULL;
$arguments8['data'] = NULL;
$arguments8['property'] = NULL;
$arguments8['class'] = NULL;
$arguments8['dir'] = NULL;
$arguments8['id'] = NULL;
$arguments8['lang'] = NULL;
$arguments8['style'] = NULL;
$arguments8['title'] = NULL;
$arguments8['accesskey'] = NULL;
$arguments8['tabindex'] = NULL;
$arguments8['onclick'] = NULL;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper

$output3 .= $viewHelper10->initializeArgumentsAndRender();

$output3 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments11 = array();
$arguments11['class'] = 'btn btn-full-width btn-primary';
$arguments11['type'] = 'submit';
$arguments11['additionalAttributes'] = NULL;
$arguments11['data'] = NULL;
$arguments11['name'] = NULL;
$arguments11['value'] = NULL;
$arguments11['property'] = NULL;
$arguments11['autofocus'] = NULL;
$arguments11['disabled'] = NULL;
$arguments11['form'] = NULL;
$arguments11['formaction'] = NULL;
$arguments11['formenctype'] = NULL;
$arguments11['formmethod'] = NULL;
$arguments11['formnovalidate'] = NULL;
$arguments11['formtarget'] = NULL;
$arguments11['dir'] = NULL;
$arguments11['id'] = NULL;
$arguments11['lang'] = NULL;
$arguments11['style'] = NULL;
$arguments11['title'] = NULL;
$arguments11['accesskey'] = NULL;
$arguments11['tabindex'] = NULL;
$arguments11['onclick'] = NULL;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return '
								<span class="glyphicon glyphicon-lock"></span> Login
							';
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output3 .= $viewHelper13->initializeArgumentsAndRender();

$output3 .= '
						</div>
					</div>
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments14 = array();
$arguments14['as'] = 'flashMessages';
$arguments14['additionalAttributes'] = NULL;
$arguments14['data'] = NULL;
$arguments14['severity'] = NULL;
$arguments14['class'] = NULL;
$arguments14['dir'] = NULL;
$arguments14['id'] = NULL;
$arguments14['lang'] = NULL;
$arguments14['style'] = NULL;
$arguments14['title'] = NULL;
$arguments14['accesskey'] = NULL;
$arguments14['tabindex'] = NULL;
$arguments14['onclick'] = NULL;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
$output16 = '';

$output16 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments17 = array();
$arguments17['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
$arguments17['as'] = 'flashMessage';
$arguments17['key'] = '';
$arguments17['reverse'] = false;
$arguments17['iteration'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
$output19 = '';

$output19 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments20 = array();
// Rendering Boolean node
$arguments20['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'OK');
$arguments20['then'] = NULL;
$arguments20['else'] = NULL;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-success">
							';
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper22->setArguments($arguments20);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output19 .= $viewHelper22->initializeArgumentsAndRender();

$output19 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments23 = array();
// Rendering Boolean node
$arguments23['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Notice');
$arguments23['then'] = NULL;
$arguments23['else'] = NULL;
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-info">
							';
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper25->setArguments($arguments23);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output19 .= $viewHelper25->initializeArgumentsAndRender();

$output19 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments26 = array();
// Rendering Boolean node
$arguments26['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Warning');
$arguments26['then'] = NULL;
$arguments26['else'] = NULL;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-warning">
							';
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper28->setArguments($arguments26);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output19 .= $viewHelper28->initializeArgumentsAndRender();

$output19 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments29 = array();
// Rendering Boolean node
$arguments29['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Error');
$arguments29['then'] = NULL;
$arguments29['else'] = NULL;
$renderChildrenClosure30 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-error">
							';
};
$viewHelper31 = $self->getViewHelper('$viewHelper31', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper31->setArguments($arguments29);
$viewHelper31->setRenderingContext($renderingContext);
$viewHelper31->setRenderChildrenClosure($renderChildrenClosure30);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output19 .= $viewHelper31->initializeArgumentsAndRender();

$output19 .= '
								<div class="tooltip-arrow tooltip-arrow-top"></div>
								<div class="tooltip-inner">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments32 = array();
$arguments32['value'] = NULL;
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.message', $renderingContext);
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output19 .= $viewHelper34->initializeArgumentsAndRender();

$output19 .= '</div>
							</div>
						';
return $output19;
};

$output16 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments17, $renderChildrenClosure18, $renderingContext);

$output16 .= '
					';
return $output16;
};
$viewHelper35 = $self->getViewHelper('$viewHelper35', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper35->setArguments($arguments14);
$viewHelper35->setRenderingContext($renderingContext);
$viewHelper35->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

$output3 .= $viewHelper35->initializeArgumentsAndRender();

$output3 .= '
				</div>
			</fieldset>
		';
return $output3;
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper36->setArguments($arguments1);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output0 .= $viewHelper36->initializeArgumentsAndRender();

$output0 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments37 = array();
// Rendering Boolean node
$arguments37['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext));
$arguments37['then'] = NULL;
$arguments37['else'] = NULL;
$renderChildrenClosure38 = function() use ($renderingContext, $self) {
$output39 = '';

$output39 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments40 = array();
$renderChildrenClosure41 = function() use ($renderingContext, $self) {
$output42 = '';

$output42 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span><strong>Initial Password:</strong> The initial password for accessing the setup can be found in the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments43 = array();
$arguments43['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext);
$arguments43['keepQuotes'] = false;
$arguments43['encoding'] = 'UTF-8';
$arguments43['doubleEncode'] = true;
$renderChildrenClosure44 = function() use ($renderingContext, $self) {
return NULL;
};
$value45 = ($arguments43['value'] !== NULL ? $arguments43['value'] : $renderChildrenClosure44());

$output42 .= !is_string($value45) && !(is_object($value45) && method_exists($value45, '__toString')) ? $value45 : htmlspecialchars($value45, ($arguments43['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments43['encoding'], $arguments43['doubleEncode']);

$output42 .= '</strong></div>
			';
return $output42;
};
$viewHelper46 = $self->getViewHelper('$viewHelper46', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper46->setArguments($arguments40);
$viewHelper46->setRenderingContext($renderingContext);
$viewHelper46->setRenderChildrenClosure($renderChildrenClosure41);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output39 .= $viewHelper46->initializeArgumentsAndRender();

$output39 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments47 = array();
$renderChildrenClosure48 = function() use ($renderingContext, $self) {
$output49 = '';

$output49 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>If you have forgotten your password, delete the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments50 = array();
$arguments50['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'existingPasswordFile', $renderingContext);
$arguments50['keepQuotes'] = false;
$arguments50['encoding'] = 'UTF-8';
$arguments50['doubleEncode'] = true;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
return NULL;
};
$value52 = ($arguments50['value'] !== NULL ? $arguments50['value'] : $renderChildrenClosure51());

$output49 .= !is_string($value52) && !(is_object($value52) && method_exists($value52, '__toString')) ? $value52 : htmlspecialchars($value52, ($arguments50['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments50['encoding'], $arguments50['doubleEncode']);

$output49 .= '</strong><br />and reload this page to generate a new password.</div>
			';
return $output49;
};
$viewHelper53 = $self->getViewHelper('$viewHelper53', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper53->setArguments($arguments47);
$viewHelper53->setRenderingContext($renderingContext);
$viewHelper53->setRenderChildrenClosure($renderChildrenClosure48);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output39 .= $viewHelper53->initializeArgumentsAndRender();

$output39 .= '
		';
return $output39;
};
$arguments37['__thenClosure'] = function() use ($renderingContext, $self) {
$output54 = '';

$output54 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span><strong>Initial Password:</strong> The initial password for accessing the setup can be found in the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments55 = array();
$arguments55['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext);
$arguments55['keepQuotes'] = false;
$arguments55['encoding'] = 'UTF-8';
$arguments55['doubleEncode'] = true;
$renderChildrenClosure56 = function() use ($renderingContext, $self) {
return NULL;
};
$value57 = ($arguments55['value'] !== NULL ? $arguments55['value'] : $renderChildrenClosure56());

$output54 .= !is_string($value57) && !(is_object($value57) && method_exists($value57, '__toString')) ? $value57 : htmlspecialchars($value57, ($arguments55['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments55['encoding'], $arguments55['doubleEncode']);

$output54 .= '</strong></div>
			';
return $output54;
};
$arguments37['__elseClosure'] = function() use ($renderingContext, $self) {
$output58 = '';

$output58 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>If you have forgotten your password, delete the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments59 = array();
$arguments59['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'existingPasswordFile', $renderingContext);
$arguments59['keepQuotes'] = false;
$arguments59['encoding'] = 'UTF-8';
$arguments59['doubleEncode'] = true;
$renderChildrenClosure60 = function() use ($renderingContext, $self) {
return NULL;
};
$value61 = ($arguments59['value'] !== NULL ? $arguments59['value'] : $renderChildrenClosure60());

$output58 .= !is_string($value61) && !(is_object($value61) && method_exists($value61, '__toString')) ? $value61 : htmlspecialchars($value61, ($arguments59['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments59['encoding'], $arguments59['doubleEncode']);

$output58 .= '</strong><br />and reload this page to generate a new password.</div>
			';
return $output58;
};
$viewHelper62 = $self->getViewHelper('$viewHelper62', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper62->setArguments($arguments37);
$viewHelper62->setRenderingContext($renderingContext);
$viewHelper62->setRenderChildrenClosure($renderChildrenClosure38);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper62->initializeArgumentsAndRender();

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
$output63 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments64 = array();
$arguments64['name'] = 'Default';
$renderChildrenClosure65 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper66 = $self->getViewHelper('$viewHelper66', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper66->setArguments($arguments64);
$viewHelper66->setRenderingContext($renderingContext);
$viewHelper66->setRenderChildrenClosure($renderChildrenClosure65);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output63 .= $viewHelper66->initializeArgumentsAndRender();

$output63 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments67 = array();
$arguments67['name'] = 'content';
$renderChildrenClosure68 = function() use ($renderingContext, $self) {
$output69 = '';

$output69 .= '
	<div class="t3-module-container indented">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments70 = array();
$arguments70['action'] = 'authenticate';
$arguments70['additionalAttributes'] = NULL;
$arguments70['data'] = NULL;
$arguments70['arguments'] = array (
);
$arguments70['controller'] = NULL;
$arguments70['package'] = NULL;
$arguments70['subpackage'] = NULL;
$arguments70['object'] = NULL;
$arguments70['section'] = '';
$arguments70['format'] = '';
$arguments70['additionalParams'] = array (
);
$arguments70['absolute'] = false;
$arguments70['addQueryString'] = false;
$arguments70['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments70['fieldNamePrefix'] = NULL;
$arguments70['actionUri'] = NULL;
$arguments70['objectName'] = NULL;
$arguments70['useParentRequest'] = false;
$arguments70['enctype'] = NULL;
$arguments70['method'] = NULL;
$arguments70['name'] = NULL;
$arguments70['onreset'] = NULL;
$arguments70['onsubmit'] = NULL;
$arguments70['class'] = NULL;
$arguments70['dir'] = NULL;
$arguments70['id'] = NULL;
$arguments70['lang'] = NULL;
$arguments70['style'] = NULL;
$arguments70['title'] = NULL;
$arguments70['accesskey'] = NULL;
$arguments70['tabindex'] = NULL;
$arguments70['onclick'] = NULL;
$renderChildrenClosure71 = function() use ($renderingContext, $self) {
$output72 = '';

$output72 .= '
			<h1 class="text-center">Login</h1>
			<fieldset>
				<legend class="text-center">Enter the setup password to continue:</legend>
				<div class="login-box">
					<div class="form-group">
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments73 = array();
$arguments73['class'] = 'form-control';
$arguments73['id'] = 'password';
$arguments73['type'] = 'password';
$arguments73['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][PasswordToken][password]';
// Rendering Array
$array74 = array();
$array74['autofocus'] = 'autofocus';
$arguments73['additionalAttributes'] = $array74;
$arguments73['placeholder'] = 'Password';
$arguments73['data'] = NULL;
$arguments73['required'] = false;
$arguments73['value'] = NULL;
$arguments73['property'] = NULL;
$arguments73['disabled'] = NULL;
$arguments73['maxlength'] = NULL;
$arguments73['readonly'] = NULL;
$arguments73['size'] = NULL;
$arguments73['autofocus'] = NULL;
$arguments73['errorClass'] = 'f3-form-error';
$arguments73['dir'] = NULL;
$arguments73['lang'] = NULL;
$arguments73['style'] = NULL;
$arguments73['title'] = NULL;
$arguments73['accesskey'] = NULL;
$arguments73['tabindex'] = NULL;
$arguments73['onclick'] = NULL;
$renderChildrenClosure75 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper76 = $self->getViewHelper('$viewHelper76', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper76->setArguments($arguments73);
$viewHelper76->setRenderingContext($renderingContext);
$viewHelper76->setRenderChildrenClosure($renderChildrenClosure75);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output72 .= $viewHelper76->initializeArgumentsAndRender();

$output72 .= '
					</div>
					<div class="form-group">
						<div class="controls">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper
$arguments77 = array();
$arguments77['name'] = 'step';
$arguments77['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'step', $renderingContext);
$arguments77['additionalAttributes'] = NULL;
$arguments77['data'] = NULL;
$arguments77['property'] = NULL;
$arguments77['class'] = NULL;
$arguments77['dir'] = NULL;
$arguments77['id'] = NULL;
$arguments77['lang'] = NULL;
$arguments77['style'] = NULL;
$arguments77['title'] = NULL;
$arguments77['accesskey'] = NULL;
$arguments77['tabindex'] = NULL;
$arguments77['onclick'] = NULL;
$renderChildrenClosure78 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper79 = $self->getViewHelper('$viewHelper79', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper');
$viewHelper79->setArguments($arguments77);
$viewHelper79->setRenderingContext($renderingContext);
$viewHelper79->setRenderChildrenClosure($renderChildrenClosure78);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper

$output72 .= $viewHelper79->initializeArgumentsAndRender();

$output72 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments80 = array();
$arguments80['class'] = 'btn btn-full-width btn-primary';
$arguments80['type'] = 'submit';
$arguments80['additionalAttributes'] = NULL;
$arguments80['data'] = NULL;
$arguments80['name'] = NULL;
$arguments80['value'] = NULL;
$arguments80['property'] = NULL;
$arguments80['autofocus'] = NULL;
$arguments80['disabled'] = NULL;
$arguments80['form'] = NULL;
$arguments80['formaction'] = NULL;
$arguments80['formenctype'] = NULL;
$arguments80['formmethod'] = NULL;
$arguments80['formnovalidate'] = NULL;
$arguments80['formtarget'] = NULL;
$arguments80['dir'] = NULL;
$arguments80['id'] = NULL;
$arguments80['lang'] = NULL;
$arguments80['style'] = NULL;
$arguments80['title'] = NULL;
$arguments80['accesskey'] = NULL;
$arguments80['tabindex'] = NULL;
$arguments80['onclick'] = NULL;
$renderChildrenClosure81 = function() use ($renderingContext, $self) {
return '
								<span class="glyphicon glyphicon-lock"></span> Login
							';
};
$viewHelper82 = $self->getViewHelper('$viewHelper82', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper82->setArguments($arguments80);
$viewHelper82->setRenderingContext($renderingContext);
$viewHelper82->setRenderChildrenClosure($renderChildrenClosure81);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output72 .= $viewHelper82->initializeArgumentsAndRender();

$output72 .= '
						</div>
					</div>
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments83 = array();
$arguments83['as'] = 'flashMessages';
$arguments83['additionalAttributes'] = NULL;
$arguments83['data'] = NULL;
$arguments83['severity'] = NULL;
$arguments83['class'] = NULL;
$arguments83['dir'] = NULL;
$arguments83['id'] = NULL;
$arguments83['lang'] = NULL;
$arguments83['style'] = NULL;
$arguments83['title'] = NULL;
$arguments83['accesskey'] = NULL;
$arguments83['tabindex'] = NULL;
$arguments83['onclick'] = NULL;
$renderChildrenClosure84 = function() use ($renderingContext, $self) {
$output85 = '';

$output85 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments86 = array();
$arguments86['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
$arguments86['as'] = 'flashMessage';
$arguments86['key'] = '';
$arguments86['reverse'] = false;
$arguments86['iteration'] = NULL;
$renderChildrenClosure87 = function() use ($renderingContext, $self) {
$output88 = '';

$output88 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments89 = array();
// Rendering Boolean node
$arguments89['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'OK');
$arguments89['then'] = NULL;
$arguments89['else'] = NULL;
$renderChildrenClosure90 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-success">
							';
};
$viewHelper91 = $self->getViewHelper('$viewHelper91', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper91->setArguments($arguments89);
$viewHelper91->setRenderingContext($renderingContext);
$viewHelper91->setRenderChildrenClosure($renderChildrenClosure90);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output88 .= $viewHelper91->initializeArgumentsAndRender();

$output88 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments92 = array();
// Rendering Boolean node
$arguments92['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Notice');
$arguments92['then'] = NULL;
$arguments92['else'] = NULL;
$renderChildrenClosure93 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-info">
							';
};
$viewHelper94 = $self->getViewHelper('$viewHelper94', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper94->setArguments($arguments92);
$viewHelper94->setRenderingContext($renderingContext);
$viewHelper94->setRenderChildrenClosure($renderChildrenClosure93);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output88 .= $viewHelper94->initializeArgumentsAndRender();

$output88 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments95 = array();
// Rendering Boolean node
$arguments95['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Warning');
$arguments95['then'] = NULL;
$arguments95['else'] = NULL;
$renderChildrenClosure96 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-warning">
							';
};
$viewHelper97 = $self->getViewHelper('$viewHelper97', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper97->setArguments($arguments95);
$viewHelper97->setRenderingContext($renderingContext);
$viewHelper97->setRenderChildrenClosure($renderChildrenClosure96);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output88 .= $viewHelper97->initializeArgumentsAndRender();

$output88 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments98 = array();
// Rendering Boolean node
$arguments98['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Error');
$arguments98['then'] = NULL;
$arguments98['else'] = NULL;
$renderChildrenClosure99 = function() use ($renderingContext, $self) {
return '
								<div class="tooltip tooltip-error">
							';
};
$viewHelper100 = $self->getViewHelper('$viewHelper100', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper100->setArguments($arguments98);
$viewHelper100->setRenderingContext($renderingContext);
$viewHelper100->setRenderChildrenClosure($renderChildrenClosure99);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output88 .= $viewHelper100->initializeArgumentsAndRender();

$output88 .= '
								<div class="tooltip-arrow tooltip-arrow-top"></div>
								<div class="tooltip-inner">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments101 = array();
$arguments101['value'] = NULL;
$renderChildrenClosure102 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.message', $renderingContext);
};
$viewHelper103 = $self->getViewHelper('$viewHelper103', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper103->setArguments($arguments101);
$viewHelper103->setRenderingContext($renderingContext);
$viewHelper103->setRenderChildrenClosure($renderChildrenClosure102);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output88 .= $viewHelper103->initializeArgumentsAndRender();

$output88 .= '</div>
							</div>
						';
return $output88;
};

$output85 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments86, $renderChildrenClosure87, $renderingContext);

$output85 .= '
					';
return $output85;
};
$viewHelper104 = $self->getViewHelper('$viewHelper104', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper104->setArguments($arguments83);
$viewHelper104->setRenderingContext($renderingContext);
$viewHelper104->setRenderChildrenClosure($renderChildrenClosure84);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

$output72 .= $viewHelper104->initializeArgumentsAndRender();

$output72 .= '
				</div>
			</fieldset>
		';
return $output72;
};
$viewHelper105 = $self->getViewHelper('$viewHelper105', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper105->setArguments($arguments70);
$viewHelper105->setRenderingContext($renderingContext);
$viewHelper105->setRenderChildrenClosure($renderChildrenClosure71);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output69 .= $viewHelper105->initializeArgumentsAndRender();

$output69 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments106 = array();
// Rendering Boolean node
$arguments106['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext));
$arguments106['then'] = NULL;
$arguments106['else'] = NULL;
$renderChildrenClosure107 = function() use ($renderingContext, $self) {
$output108 = '';

$output108 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments109 = array();
$renderChildrenClosure110 = function() use ($renderingContext, $self) {
$output111 = '';

$output111 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span><strong>Initial Password:</strong> The initial password for accessing the setup can be found in the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments112 = array();
$arguments112['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext);
$arguments112['keepQuotes'] = false;
$arguments112['encoding'] = 'UTF-8';
$arguments112['doubleEncode'] = true;
$renderChildrenClosure113 = function() use ($renderingContext, $self) {
return NULL;
};
$value114 = ($arguments112['value'] !== NULL ? $arguments112['value'] : $renderChildrenClosure113());

$output111 .= !is_string($value114) && !(is_object($value114) && method_exists($value114, '__toString')) ? $value114 : htmlspecialchars($value114, ($arguments112['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments112['encoding'], $arguments112['doubleEncode']);

$output111 .= '</strong></div>
			';
return $output111;
};
$viewHelper115 = $self->getViewHelper('$viewHelper115', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper115->setArguments($arguments109);
$viewHelper115->setRenderingContext($renderingContext);
$viewHelper115->setRenderChildrenClosure($renderChildrenClosure110);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output108 .= $viewHelper115->initializeArgumentsAndRender();

$output108 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments116 = array();
$renderChildrenClosure117 = function() use ($renderingContext, $self) {
$output118 = '';

$output118 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>If you have forgotten your password, delete the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments119 = array();
$arguments119['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'existingPasswordFile', $renderingContext);
$arguments119['keepQuotes'] = false;
$arguments119['encoding'] = 'UTF-8';
$arguments119['doubleEncode'] = true;
$renderChildrenClosure120 = function() use ($renderingContext, $self) {
return NULL;
};
$value121 = ($arguments119['value'] !== NULL ? $arguments119['value'] : $renderChildrenClosure120());

$output118 .= !is_string($value121) && !(is_object($value121) && method_exists($value121, '__toString')) ? $value121 : htmlspecialchars($value121, ($arguments119['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments119['encoding'], $arguments119['doubleEncode']);

$output118 .= '</strong><br />and reload this page to generate a new password.</div>
			';
return $output118;
};
$viewHelper122 = $self->getViewHelper('$viewHelper122', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper122->setArguments($arguments116);
$viewHelper122->setRenderingContext($renderingContext);
$viewHelper122->setRenderChildrenClosure($renderChildrenClosure117);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output108 .= $viewHelper122->initializeArgumentsAndRender();

$output108 .= '
		';
return $output108;
};
$arguments106['__thenClosure'] = function() use ($renderingContext, $self) {
$output123 = '';

$output123 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span><strong>Initial Password:</strong> The initial password for accessing the setup can be found in the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments124 = array();
$arguments124['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'initialPasswordFile', $renderingContext);
$arguments124['keepQuotes'] = false;
$arguments124['encoding'] = 'UTF-8';
$arguments124['doubleEncode'] = true;
$renderChildrenClosure125 = function() use ($renderingContext, $self) {
return NULL;
};
$value126 = ($arguments124['value'] !== NULL ? $arguments124['value'] : $renderChildrenClosure125());

$output123 .= !is_string($value126) && !(is_object($value126) && method_exists($value126, '__toString')) ? $value126 : htmlspecialchars($value126, ($arguments124['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments124['encoding'], $arguments124['doubleEncode']);

$output123 .= '</strong></div>
			';
return $output123;
};
$arguments106['__elseClosure'] = function() use ($renderingContext, $self) {
$output127 = '';

$output127 .= '
				<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>If you have forgotten your password, delete the file<br /><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments128 = array();
$arguments128['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'existingPasswordFile', $renderingContext);
$arguments128['keepQuotes'] = false;
$arguments128['encoding'] = 'UTF-8';
$arguments128['doubleEncode'] = true;
$renderChildrenClosure129 = function() use ($renderingContext, $self) {
return NULL;
};
$value130 = ($arguments128['value'] !== NULL ? $arguments128['value'] : $renderChildrenClosure129());

$output127 .= !is_string($value130) && !(is_object($value130) && method_exists($value130, '__toString')) ? $value130 : htmlspecialchars($value130, ($arguments128['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments128['encoding'], $arguments128['doubleEncode']);

$output127 .= '</strong><br />and reload this page to generate a new password.</div>
			';
return $output127;
};
$viewHelper131 = $self->getViewHelper('$viewHelper131', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper131->setArguments($arguments106);
$viewHelper131->setRenderingContext($renderingContext);
$viewHelper131->setRenderChildrenClosure($renderChildrenClosure107);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output69 .= $viewHelper131->initializeArgumentsAndRender();

$output69 .= '
	</div>
';
return $output69;
};

$output63 .= '';

return $output63;
}


}
#0             41929     