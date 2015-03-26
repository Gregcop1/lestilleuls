<?php class FluidCache_TYPO3_Neos_Login_action_index_a2b652c5430793c991bf2b6ebf7949bf460135ff extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
 * section head
 */
public function section_1a954628a960aaef81d7b2d4521929579f3541e6(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<title>TYPO3 Neos Login</title>
	<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments1 = array();
$arguments1['path'] = 'Styles/Login.css';
$arguments1['package'] = NULL;
$arguments1['resource'] = NULL;
$arguments1['localize'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '" />
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments4 = array();
$arguments4['path'] = 'Library/jquery/jquery-2.0.3.js';
$arguments4['package'] = NULL;
$arguments4['resource'] = NULL;
$arguments4['localize'] = true;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper6->initializeArgumentsAndRender();

$output0 .= '"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments7 = array();
$arguments7['path'] = 'Library/jquery-ui/js/jquery-ui-1.10.4.custom.js';
$arguments7['package'] = NULL;
$arguments7['resource'] = NULL;
$arguments7['localize'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper9 = $self->getViewHelper('$viewHelper9', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper9->setArguments($arguments7);
$viewHelper9->setRenderingContext($renderingContext);
$viewHelper9->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper9->initializeArgumentsAndRender();

$output0 .= '"></script>
';

return $output0;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output10 = '';

$output10 .= '
	<body class="neos">
		<div id="neos-login-box">
			<div class="neos-login-logo">
				<img src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments11 = array();
$arguments11['path'] = 'Images/Login/ApplicationLogo.png';
$arguments11['package'] = NULL;
$arguments11['resource'] = NULL;
$arguments11['localize'] = true;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output10 .= $viewHelper13->initializeArgumentsAndRender();

$output10 .= '" alt="TYPO3 Neos" />
			</div>
			<div class="neos-login-body neos">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments14 = array();
$arguments14['name'] = 'login';
$arguments14['action'] = 'authenticate';
$arguments14['additionalAttributes'] = NULL;
$arguments14['data'] = NULL;
$arguments14['arguments'] = array (
);
$arguments14['controller'] = NULL;
$arguments14['package'] = NULL;
$arguments14['subpackage'] = NULL;
$arguments14['object'] = NULL;
$arguments14['section'] = '';
$arguments14['format'] = '';
$arguments14['additionalParams'] = array (
);
$arguments14['absolute'] = false;
$arguments14['addQueryString'] = false;
$arguments14['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments14['fieldNamePrefix'] = NULL;
$arguments14['actionUri'] = NULL;
$arguments14['objectName'] = NULL;
$arguments14['useParentRequest'] = false;
$arguments14['enctype'] = NULL;
$arguments14['method'] = NULL;
$arguments14['onreset'] = NULL;
$arguments14['onsubmit'] = NULL;
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
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper
$arguments17 = array();
$arguments17['name'] = 'lastVisitedNode';
$arguments17['additionalAttributes'] = NULL;
$arguments17['data'] = NULL;
$arguments17['value'] = NULL;
$arguments17['property'] = NULL;
$arguments17['class'] = NULL;
$arguments17['dir'] = NULL;
$arguments17['id'] = NULL;
$arguments17['lang'] = NULL;
$arguments17['style'] = NULL;
$arguments17['title'] = NULL;
$arguments17['accesskey'] = NULL;
$arguments17['tabindex'] = NULL;
$arguments17['onclick'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper19 = $self->getViewHelper('$viewHelper19', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper');
$viewHelper19->setArguments($arguments17);
$viewHelper19->setRenderingContext($renderingContext);
$viewHelper19->setRenderChildrenClosure($renderChildrenClosure18);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper

$output16 .= $viewHelper19->initializeArgumentsAndRender();

$output16 .= '
					<fieldset>
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments20 = array();
// Rendering Boolean node
$arguments20['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext));
$arguments20['then'] = NULL;
$arguments20['else'] = NULL;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
$output22 = '';

$output22 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments23 = array();
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
$output25 = '';

$output25 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments26 = array();
// Rendering Boolean node
$arguments26['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments26['id'] = 'username';
$arguments26['type'] = 'text';
$arguments26['placeholder'] = 'Username';
$arguments26['class'] = 'neos-span12';
$arguments26['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
$arguments26['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext);
$arguments26['additionalAttributes'] = NULL;
$arguments26['data'] = NULL;
$arguments26['property'] = NULL;
$arguments26['disabled'] = NULL;
$arguments26['maxlength'] = NULL;
$arguments26['readonly'] = NULL;
$arguments26['size'] = NULL;
$arguments26['autofocus'] = NULL;
$arguments26['errorClass'] = 'f3-form-error';
$arguments26['dir'] = NULL;
$arguments26['lang'] = NULL;
$arguments26['style'] = NULL;
$arguments26['title'] = NULL;
$arguments26['accesskey'] = NULL;
$arguments26['tabindex'] = NULL;
$arguments26['onclick'] = NULL;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper28->setArguments($arguments26);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output25 .= $viewHelper28->initializeArgumentsAndRender();

$output25 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments29 = array();
// Rendering Boolean node
$arguments29['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments29['id'] = 'password';
$arguments29['type'] = 'password';
$arguments29['placeholder'] = 'Password';
$arguments29['class'] = 'neos-span12';
$arguments29['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
// Rendering Array
$array30 = array();
$array30['autofocus'] = 'autofocus';
$arguments29['additionalAttributes'] = $array30;
$arguments29['data'] = NULL;
$arguments29['value'] = NULL;
$arguments29['property'] = NULL;
$arguments29['disabled'] = NULL;
$arguments29['maxlength'] = NULL;
$arguments29['readonly'] = NULL;
$arguments29['size'] = NULL;
$arguments29['autofocus'] = NULL;
$arguments29['errorClass'] = 'f3-form-error';
$arguments29['dir'] = NULL;
$arguments29['lang'] = NULL;
$arguments29['style'] = NULL;
$arguments29['title'] = NULL;
$arguments29['accesskey'] = NULL;
$arguments29['tabindex'] = NULL;
$arguments29['onclick'] = NULL;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper32 = $self->getViewHelper('$viewHelper32', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper32->setArguments($arguments29);
$viewHelper32->setRenderingContext($renderingContext);
$viewHelper32->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output25 .= $viewHelper32->initializeArgumentsAndRender();

$output25 .= '
									</div>
								';
return $output25;
};
$viewHelper33 = $self->getViewHelper('$viewHelper33', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper33->setArguments($arguments23);
$viewHelper33->setRenderingContext($renderingContext);
$viewHelper33->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output22 .= $viewHelper33->initializeArgumentsAndRender();

$output22 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments34 = array();
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
$output36 = '';

$output36 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments37 = array();
// Rendering Boolean node
$arguments37['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments37['id'] = 'username';
$arguments37['type'] = 'text';
$arguments37['placeholder'] = 'Username';
$arguments37['class'] = 'neos-span12';
$arguments37['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
// Rendering Array
$array38 = array();
$array38['autofocus'] = 'autofocus';
$arguments37['additionalAttributes'] = $array38;
$arguments37['data'] = NULL;
$arguments37['value'] = NULL;
$arguments37['property'] = NULL;
$arguments37['disabled'] = NULL;
$arguments37['maxlength'] = NULL;
$arguments37['readonly'] = NULL;
$arguments37['size'] = NULL;
$arguments37['autofocus'] = NULL;
$arguments37['errorClass'] = 'f3-form-error';
$arguments37['dir'] = NULL;
$arguments37['lang'] = NULL;
$arguments37['style'] = NULL;
$arguments37['title'] = NULL;
$arguments37['accesskey'] = NULL;
$arguments37['tabindex'] = NULL;
$arguments37['onclick'] = NULL;
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper40 = $self->getViewHelper('$viewHelper40', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper40->setArguments($arguments37);
$viewHelper40->setRenderingContext($renderingContext);
$viewHelper40->setRenderChildrenClosure($renderChildrenClosure39);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output36 .= $viewHelper40->initializeArgumentsAndRender();

$output36 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments41 = array();
// Rendering Boolean node
$arguments41['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments41['id'] = 'password';
$arguments41['type'] = 'password';
$arguments41['placeholder'] = 'Password';
$arguments41['class'] = 'neos-span12';
$arguments41['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
$arguments41['additionalAttributes'] = NULL;
$arguments41['data'] = NULL;
$arguments41['value'] = NULL;
$arguments41['property'] = NULL;
$arguments41['disabled'] = NULL;
$arguments41['maxlength'] = NULL;
$arguments41['readonly'] = NULL;
$arguments41['size'] = NULL;
$arguments41['autofocus'] = NULL;
$arguments41['errorClass'] = 'f3-form-error';
$arguments41['dir'] = NULL;
$arguments41['lang'] = NULL;
$arguments41['style'] = NULL;
$arguments41['title'] = NULL;
$arguments41['accesskey'] = NULL;
$arguments41['tabindex'] = NULL;
$arguments41['onclick'] = NULL;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper43 = $self->getViewHelper('$viewHelper43', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper43->setArguments($arguments41);
$viewHelper43->setRenderingContext($renderingContext);
$viewHelper43->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output36 .= $viewHelper43->initializeArgumentsAndRender();

$output36 .= '
									</div>
								';
return $output36;
};
$viewHelper44 = $self->getViewHelper('$viewHelper44', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper44->setArguments($arguments34);
$viewHelper44->setRenderingContext($renderingContext);
$viewHelper44->setRenderChildrenClosure($renderChildrenClosure35);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output22 .= $viewHelper44->initializeArgumentsAndRender();

$output22 .= '
							';
return $output22;
};
$arguments20['__thenClosure'] = function() use ($renderingContext, $self) {
$output45 = '';

$output45 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments46 = array();
// Rendering Boolean node
$arguments46['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments46['id'] = 'username';
$arguments46['type'] = 'text';
$arguments46['placeholder'] = 'Username';
$arguments46['class'] = 'neos-span12';
$arguments46['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
$arguments46['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext);
$arguments46['additionalAttributes'] = NULL;
$arguments46['data'] = NULL;
$arguments46['property'] = NULL;
$arguments46['disabled'] = NULL;
$arguments46['maxlength'] = NULL;
$arguments46['readonly'] = NULL;
$arguments46['size'] = NULL;
$arguments46['autofocus'] = NULL;
$arguments46['errorClass'] = 'f3-form-error';
$arguments46['dir'] = NULL;
$arguments46['lang'] = NULL;
$arguments46['style'] = NULL;
$arguments46['title'] = NULL;
$arguments46['accesskey'] = NULL;
$arguments46['tabindex'] = NULL;
$arguments46['onclick'] = NULL;
$renderChildrenClosure47 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper48->setArguments($arguments46);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure47);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output45 .= $viewHelper48->initializeArgumentsAndRender();

$output45 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments49 = array();
// Rendering Boolean node
$arguments49['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments49['id'] = 'password';
$arguments49['type'] = 'password';
$arguments49['placeholder'] = 'Password';
$arguments49['class'] = 'neos-span12';
$arguments49['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
// Rendering Array
$array50 = array();
$array50['autofocus'] = 'autofocus';
$arguments49['additionalAttributes'] = $array50;
$arguments49['data'] = NULL;
$arguments49['value'] = NULL;
$arguments49['property'] = NULL;
$arguments49['disabled'] = NULL;
$arguments49['maxlength'] = NULL;
$arguments49['readonly'] = NULL;
$arguments49['size'] = NULL;
$arguments49['autofocus'] = NULL;
$arguments49['errorClass'] = 'f3-form-error';
$arguments49['dir'] = NULL;
$arguments49['lang'] = NULL;
$arguments49['style'] = NULL;
$arguments49['title'] = NULL;
$arguments49['accesskey'] = NULL;
$arguments49['tabindex'] = NULL;
$arguments49['onclick'] = NULL;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper52 = $self->getViewHelper('$viewHelper52', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper52->setArguments($arguments49);
$viewHelper52->setRenderingContext($renderingContext);
$viewHelper52->setRenderChildrenClosure($renderChildrenClosure51);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output45 .= $viewHelper52->initializeArgumentsAndRender();

$output45 .= '
									</div>
								';
return $output45;
};
$arguments20['__elseClosure'] = function() use ($renderingContext, $self) {
$output53 = '';

$output53 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments54 = array();
// Rendering Boolean node
$arguments54['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments54['id'] = 'username';
$arguments54['type'] = 'text';
$arguments54['placeholder'] = 'Username';
$arguments54['class'] = 'neos-span12';
$arguments54['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
// Rendering Array
$array55 = array();
$array55['autofocus'] = 'autofocus';
$arguments54['additionalAttributes'] = $array55;
$arguments54['data'] = NULL;
$arguments54['value'] = NULL;
$arguments54['property'] = NULL;
$arguments54['disabled'] = NULL;
$arguments54['maxlength'] = NULL;
$arguments54['readonly'] = NULL;
$arguments54['size'] = NULL;
$arguments54['autofocus'] = NULL;
$arguments54['errorClass'] = 'f3-form-error';
$arguments54['dir'] = NULL;
$arguments54['lang'] = NULL;
$arguments54['style'] = NULL;
$arguments54['title'] = NULL;
$arguments54['accesskey'] = NULL;
$arguments54['tabindex'] = NULL;
$arguments54['onclick'] = NULL;
$renderChildrenClosure56 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper57 = $self->getViewHelper('$viewHelper57', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper57->setArguments($arguments54);
$viewHelper57->setRenderingContext($renderingContext);
$viewHelper57->setRenderChildrenClosure($renderChildrenClosure56);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output53 .= $viewHelper57->initializeArgumentsAndRender();

$output53 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments58 = array();
// Rendering Boolean node
$arguments58['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments58['id'] = 'password';
$arguments58['type'] = 'password';
$arguments58['placeholder'] = 'Password';
$arguments58['class'] = 'neos-span12';
$arguments58['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
$arguments58['additionalAttributes'] = NULL;
$arguments58['data'] = NULL;
$arguments58['value'] = NULL;
$arguments58['property'] = NULL;
$arguments58['disabled'] = NULL;
$arguments58['maxlength'] = NULL;
$arguments58['readonly'] = NULL;
$arguments58['size'] = NULL;
$arguments58['autofocus'] = NULL;
$arguments58['errorClass'] = 'f3-form-error';
$arguments58['dir'] = NULL;
$arguments58['lang'] = NULL;
$arguments58['style'] = NULL;
$arguments58['title'] = NULL;
$arguments58['accesskey'] = NULL;
$arguments58['tabindex'] = NULL;
$arguments58['onclick'] = NULL;
$renderChildrenClosure59 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper60 = $self->getViewHelper('$viewHelper60', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper60->setArguments($arguments58);
$viewHelper60->setRenderingContext($renderingContext);
$viewHelper60->setRenderChildrenClosure($renderChildrenClosure59);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output53 .= $viewHelper60->initializeArgumentsAndRender();

$output53 .= '
									</div>
								';
return $output53;
};
$viewHelper61 = $self->getViewHelper('$viewHelper61', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper61->setArguments($arguments20);
$viewHelper61->setRenderingContext($renderingContext);
$viewHelper61->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output16 .= $viewHelper61->initializeArgumentsAndRender();

$output16 .= '
						<div class="neos-actions">
							<!-- Forgot password link will be here -->
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments62 = array();
$arguments62['type'] = 'submit';
$arguments62['class'] = 'neos-span5 neos-pull-right neos-button neos-login-btn';
$arguments62['additionalAttributes'] = NULL;
$arguments62['data'] = NULL;
$arguments62['name'] = NULL;
$arguments62['value'] = NULL;
$arguments62['property'] = NULL;
$arguments62['autofocus'] = NULL;
$arguments62['disabled'] = NULL;
$arguments62['form'] = NULL;
$arguments62['formaction'] = NULL;
$arguments62['formenctype'] = NULL;
$arguments62['formmethod'] = NULL;
$arguments62['formnovalidate'] = NULL;
$arguments62['formtarget'] = NULL;
$arguments62['dir'] = NULL;
$arguments62['id'] = NULL;
$arguments62['lang'] = NULL;
$arguments62['style'] = NULL;
$arguments62['title'] = NULL;
$arguments62['accesskey'] = NULL;
$arguments62['tabindex'] = NULL;
$arguments62['onclick'] = NULL;
$renderChildrenClosure63 = function() use ($renderingContext, $self) {
return '
								Login
							';
};
$viewHelper64 = $self->getViewHelper('$viewHelper64', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper64->setArguments($arguments62);
$viewHelper64->setRenderingContext($renderingContext);
$viewHelper64->setRenderChildrenClosure($renderChildrenClosure63);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output16 .= $viewHelper64->initializeArgumentsAndRender();

$output16 .= '
							<button class="neos-span5 neos-pull-right neos-button neos-login-btn neos-disabled neos-hidden">
								Authenticating<span class="neos-ellipsis"></span>
							</button>
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments65 = array();
$arguments65['as'] = 'flashMessages';
$arguments65['additionalAttributes'] = NULL;
$arguments65['data'] = NULL;
$arguments65['severity'] = NULL;
$arguments65['class'] = NULL;
$arguments65['dir'] = NULL;
$arguments65['id'] = NULL;
$arguments65['lang'] = NULL;
$arguments65['style'] = NULL;
$arguments65['title'] = NULL;
$arguments65['accesskey'] = NULL;
$arguments65['tabindex'] = NULL;
$arguments65['onclick'] = NULL;
$renderChildrenClosure66 = function() use ($renderingContext, $self) {
$output67 = '';

$output67 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments68 = array();
$arguments68['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
$arguments68['as'] = 'flashMessage';
$arguments68['key'] = '';
$arguments68['reverse'] = false;
$arguments68['iteration'] = NULL;
$renderChildrenClosure69 = function() use ($renderingContext, $self) {
$output70 = '';

$output70 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments71 = array();
// Rendering Boolean node
$arguments71['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'OK');
$arguments71['then'] = NULL;
$arguments71['else'] = NULL;
$renderChildrenClosure72 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-success">
									';
};
$viewHelper73 = $self->getViewHelper('$viewHelper73', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper73->setArguments($arguments71);
$viewHelper73->setRenderingContext($renderingContext);
$viewHelper73->setRenderChildrenClosure($renderChildrenClosure72);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output70 .= $viewHelper73->initializeArgumentsAndRender();

$output70 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments74 = array();
// Rendering Boolean node
$arguments74['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Notice');
$arguments74['then'] = NULL;
$arguments74['else'] = NULL;
$renderChildrenClosure75 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-notice">
									';
};
$viewHelper76 = $self->getViewHelper('$viewHelper76', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper76->setArguments($arguments74);
$viewHelper76->setRenderingContext($renderingContext);
$viewHelper76->setRenderChildrenClosure($renderChildrenClosure75);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output70 .= $viewHelper76->initializeArgumentsAndRender();

$output70 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments77 = array();
// Rendering Boolean node
$arguments77['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Warning');
$arguments77['then'] = NULL;
$arguments77['else'] = NULL;
$renderChildrenClosure78 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-warning">
									';
};
$viewHelper79 = $self->getViewHelper('$viewHelper79', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper79->setArguments($arguments77);
$viewHelper79->setRenderingContext($renderingContext);
$viewHelper79->setRenderChildrenClosure($renderChildrenClosure78);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output70 .= $viewHelper79->initializeArgumentsAndRender();

$output70 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments80 = array();
// Rendering Boolean node
$arguments80['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Error');
$arguments80['then'] = NULL;
$arguments80['else'] = NULL;
$renderChildrenClosure81 = function() use ($renderingContext, $self) {
$output82 = '';

$output82 .= '
										<script>
											$(function() {
												$(\'fieldset\').effect(\'shake\', ';

$output82 .= '{times: 1}';

$output82 .= ', 60);
											});
										</script>
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-error">
									';
return $output82;
};
$viewHelper83 = $self->getViewHelper('$viewHelper83', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper83->setArguments($arguments80);
$viewHelper83->setRenderingContext($renderingContext);
$viewHelper83->setRenderChildrenClosure($renderChildrenClosure81);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output70 .= $viewHelper83->initializeArgumentsAndRender();

$output70 .= '
										<div class="neos-tooltip-arrow"></div>
										<div class="neos-tooltip-inner">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments84 = array();
$arguments84['value'] = NULL;
$renderChildrenClosure85 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.message', $renderingContext);
};
$viewHelper86 = $self->getViewHelper('$viewHelper86', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper86->setArguments($arguments84);
$viewHelper86->setRenderingContext($renderingContext);
$viewHelper86->setRenderChildrenClosure($renderChildrenClosure85);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output70 .= $viewHelper86->initializeArgumentsAndRender();

$output70 .= '</div>
									</div>
								';
return $output70;
};

$output67 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments68, $renderChildrenClosure69, $renderingContext);

$output67 .= '
							';
return $output67;
};
$viewHelper87 = $self->getViewHelper('$viewHelper87', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper87->setArguments($arguments65);
$viewHelper87->setRenderingContext($renderingContext);
$viewHelper87->setRenderChildrenClosure($renderChildrenClosure66);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

$output16 .= $viewHelper87->initializeArgumentsAndRender();

$output16 .= '
						</div>
					</fieldset>
				';
return $output16;
};
$viewHelper88 = $self->getViewHelper('$viewHelper88', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper88->setArguments($arguments14);
$viewHelper88->setRenderingContext($renderingContext);
$viewHelper88->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output10 .= $viewHelper88->initializeArgumentsAndRender();

$output10 .= '
			</div>
		</div>
		<div id="neos-login-footer">
			<p>
				<a href="http://neos.typo3.org" target="_blank">TYPO3 Neos</a> – © 2006-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\DateViewHelper
$arguments89 = array();
$arguments89['format'] = 'Y';
$arguments89['date'] = 'now';
$arguments89['forceLocale'] = NULL;
$arguments89['localeFormatType'] = NULL;
$arguments89['localeFormatLength'] = NULL;
$arguments89['cldrFormat'] = NULL;
$renderChildrenClosure90 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper91 = $self->getViewHelper('$viewHelper91', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\DateViewHelper');
$viewHelper91->setArguments($arguments89);
$viewHelper91->setRenderingContext($renderingContext);
$viewHelper91->setRenderChildrenClosure($renderChildrenClosure90);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\DateViewHelper

$output10 .= $viewHelper91->initializeArgumentsAndRender();

$output10 .= '
				This is free software, licensed under GPL3 or higher, and you are welcome to redistribute it under certain conditions; <a href="http://typo3.org/licenses" target="_blank">click for details.</a>
				TYPO3 Neos comes with ABSOLUTELY NO WARRANTY; <a href="http://typo3.org/licenses" target="_blank">click for details.</a>
				See <a href="http://neos.typo3.org" target="_blank">neos.typo3.org</a> for more details.
				Obstructing the appearance of this notice is prohibited by law.
			</p>
		</div>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments92 = array();
$arguments92['path'] = '2/js/bootstrap.min.js';
$arguments92['package'] = 'TYPO3.Twitter.Bootstrap';
$arguments92['resource'] = NULL;
$arguments92['localize'] = true;
$renderChildrenClosure93 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper94 = $self->getViewHelper('$viewHelper94', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper94->setArguments($arguments92);
$viewHelper94->setRenderingContext($renderingContext);
$viewHelper94->setRenderChildrenClosure($renderChildrenClosure93);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output10 .= $viewHelper94->initializeArgumentsAndRender();

$output10 .= '"></script>
		<script>
			if ($(\'#username\').val()) {
				$(\'#password\').focus();
			}
			$(\'form\').on(\'submit\', function() {
				$(\'.neos-login-btn\').toggleClass(\'neos-hidden\');
			});
			try {
				$(\'form[name="login"] input[name="lastVisitedNode"]\').val(sessionStorage.getItem(\'TYPO3.Neos.lastVisitedNode\'));
			} catch(e) {}
		</script>
	</body>
';

return $output10;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output95 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments96 = array();
$arguments96['name'] = 'Default';
$renderChildrenClosure97 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper98 = $self->getViewHelper('$viewHelper98', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper98->setArguments($arguments96);
$viewHelper98->setRenderingContext($renderingContext);
$viewHelper98->setRenderChildrenClosure($renderChildrenClosure97);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output95 .= $viewHelper98->initializeArgumentsAndRender();

$output95 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments99 = array();
$arguments99['name'] = 'head';
$renderChildrenClosure100 = function() use ($renderingContext, $self) {
$output101 = '';

$output101 .= '
	<title>TYPO3 Neos Login</title>
	<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments102 = array();
$arguments102['path'] = 'Styles/Login.css';
$arguments102['package'] = NULL;
$arguments102['resource'] = NULL;
$arguments102['localize'] = true;
$renderChildrenClosure103 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper104 = $self->getViewHelper('$viewHelper104', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper104->setArguments($arguments102);
$viewHelper104->setRenderingContext($renderingContext);
$viewHelper104->setRenderChildrenClosure($renderChildrenClosure103);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output101 .= $viewHelper104->initializeArgumentsAndRender();

$output101 .= '" />
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments105 = array();
$arguments105['path'] = 'Library/jquery/jquery-2.0.3.js';
$arguments105['package'] = NULL;
$arguments105['resource'] = NULL;
$arguments105['localize'] = true;
$renderChildrenClosure106 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper107 = $self->getViewHelper('$viewHelper107', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper107->setArguments($arguments105);
$viewHelper107->setRenderingContext($renderingContext);
$viewHelper107->setRenderChildrenClosure($renderChildrenClosure106);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output101 .= $viewHelper107->initializeArgumentsAndRender();

$output101 .= '"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments108 = array();
$arguments108['path'] = 'Library/jquery-ui/js/jquery-ui-1.10.4.custom.js';
$arguments108['package'] = NULL;
$arguments108['resource'] = NULL;
$arguments108['localize'] = true;
$renderChildrenClosure109 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper110 = $self->getViewHelper('$viewHelper110', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper110->setArguments($arguments108);
$viewHelper110->setRenderingContext($renderingContext);
$viewHelper110->setRenderChildrenClosure($renderChildrenClosure109);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output101 .= $viewHelper110->initializeArgumentsAndRender();

$output101 .= '"></script>
';
return $output101;
};

$output95 .= '';

$output95 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments111 = array();
$arguments111['name'] = 'body';
$renderChildrenClosure112 = function() use ($renderingContext, $self) {
$output113 = '';

$output113 .= '
	<body class="neos">
		<div id="neos-login-box">
			<div class="neos-login-logo">
				<img src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments114 = array();
$arguments114['path'] = 'Images/Login/ApplicationLogo.png';
$arguments114['package'] = NULL;
$arguments114['resource'] = NULL;
$arguments114['localize'] = true;
$renderChildrenClosure115 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper116 = $self->getViewHelper('$viewHelper116', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper116->setArguments($arguments114);
$viewHelper116->setRenderingContext($renderingContext);
$viewHelper116->setRenderChildrenClosure($renderChildrenClosure115);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output113 .= $viewHelper116->initializeArgumentsAndRender();

$output113 .= '" alt="TYPO3 Neos" />
			</div>
			<div class="neos-login-body neos">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments117 = array();
$arguments117['name'] = 'login';
$arguments117['action'] = 'authenticate';
$arguments117['additionalAttributes'] = NULL;
$arguments117['data'] = NULL;
$arguments117['arguments'] = array (
);
$arguments117['controller'] = NULL;
$arguments117['package'] = NULL;
$arguments117['subpackage'] = NULL;
$arguments117['object'] = NULL;
$arguments117['section'] = '';
$arguments117['format'] = '';
$arguments117['additionalParams'] = array (
);
$arguments117['absolute'] = false;
$arguments117['addQueryString'] = false;
$arguments117['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments117['fieldNamePrefix'] = NULL;
$arguments117['actionUri'] = NULL;
$arguments117['objectName'] = NULL;
$arguments117['useParentRequest'] = false;
$arguments117['enctype'] = NULL;
$arguments117['method'] = NULL;
$arguments117['onreset'] = NULL;
$arguments117['onsubmit'] = NULL;
$arguments117['class'] = NULL;
$arguments117['dir'] = NULL;
$arguments117['id'] = NULL;
$arguments117['lang'] = NULL;
$arguments117['style'] = NULL;
$arguments117['title'] = NULL;
$arguments117['accesskey'] = NULL;
$arguments117['tabindex'] = NULL;
$arguments117['onclick'] = NULL;
$renderChildrenClosure118 = function() use ($renderingContext, $self) {
$output119 = '';

$output119 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper
$arguments120 = array();
$arguments120['name'] = 'lastVisitedNode';
$arguments120['additionalAttributes'] = NULL;
$arguments120['data'] = NULL;
$arguments120['value'] = NULL;
$arguments120['property'] = NULL;
$arguments120['class'] = NULL;
$arguments120['dir'] = NULL;
$arguments120['id'] = NULL;
$arguments120['lang'] = NULL;
$arguments120['style'] = NULL;
$arguments120['title'] = NULL;
$arguments120['accesskey'] = NULL;
$arguments120['tabindex'] = NULL;
$arguments120['onclick'] = NULL;
$renderChildrenClosure121 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper122 = $self->getViewHelper('$viewHelper122', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper');
$viewHelper122->setArguments($arguments120);
$viewHelper122->setRenderingContext($renderingContext);
$viewHelper122->setRenderChildrenClosure($renderChildrenClosure121);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\HiddenViewHelper

$output119 .= $viewHelper122->initializeArgumentsAndRender();

$output119 .= '
					<fieldset>
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments123 = array();
// Rendering Boolean node
$arguments123['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext));
$arguments123['then'] = NULL;
$arguments123['else'] = NULL;
$renderChildrenClosure124 = function() use ($renderingContext, $self) {
$output125 = '';

$output125 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments126 = array();
$renderChildrenClosure127 = function() use ($renderingContext, $self) {
$output128 = '';

$output128 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments129 = array();
// Rendering Boolean node
$arguments129['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments129['id'] = 'username';
$arguments129['type'] = 'text';
$arguments129['placeholder'] = 'Username';
$arguments129['class'] = 'neos-span12';
$arguments129['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
$arguments129['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext);
$arguments129['additionalAttributes'] = NULL;
$arguments129['data'] = NULL;
$arguments129['property'] = NULL;
$arguments129['disabled'] = NULL;
$arguments129['maxlength'] = NULL;
$arguments129['readonly'] = NULL;
$arguments129['size'] = NULL;
$arguments129['autofocus'] = NULL;
$arguments129['errorClass'] = 'f3-form-error';
$arguments129['dir'] = NULL;
$arguments129['lang'] = NULL;
$arguments129['style'] = NULL;
$arguments129['title'] = NULL;
$arguments129['accesskey'] = NULL;
$arguments129['tabindex'] = NULL;
$arguments129['onclick'] = NULL;
$renderChildrenClosure130 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper131 = $self->getViewHelper('$viewHelper131', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper131->setArguments($arguments129);
$viewHelper131->setRenderingContext($renderingContext);
$viewHelper131->setRenderChildrenClosure($renderChildrenClosure130);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output128 .= $viewHelper131->initializeArgumentsAndRender();

$output128 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments132 = array();
// Rendering Boolean node
$arguments132['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments132['id'] = 'password';
$arguments132['type'] = 'password';
$arguments132['placeholder'] = 'Password';
$arguments132['class'] = 'neos-span12';
$arguments132['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
// Rendering Array
$array133 = array();
$array133['autofocus'] = 'autofocus';
$arguments132['additionalAttributes'] = $array133;
$arguments132['data'] = NULL;
$arguments132['value'] = NULL;
$arguments132['property'] = NULL;
$arguments132['disabled'] = NULL;
$arguments132['maxlength'] = NULL;
$arguments132['readonly'] = NULL;
$arguments132['size'] = NULL;
$arguments132['autofocus'] = NULL;
$arguments132['errorClass'] = 'f3-form-error';
$arguments132['dir'] = NULL;
$arguments132['lang'] = NULL;
$arguments132['style'] = NULL;
$arguments132['title'] = NULL;
$arguments132['accesskey'] = NULL;
$arguments132['tabindex'] = NULL;
$arguments132['onclick'] = NULL;
$renderChildrenClosure134 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper135 = $self->getViewHelper('$viewHelper135', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper135->setArguments($arguments132);
$viewHelper135->setRenderingContext($renderingContext);
$viewHelper135->setRenderChildrenClosure($renderChildrenClosure134);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output128 .= $viewHelper135->initializeArgumentsAndRender();

$output128 .= '
									</div>
								';
return $output128;
};
$viewHelper136 = $self->getViewHelper('$viewHelper136', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper136->setArguments($arguments126);
$viewHelper136->setRenderingContext($renderingContext);
$viewHelper136->setRenderChildrenClosure($renderChildrenClosure127);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output125 .= $viewHelper136->initializeArgumentsAndRender();

$output125 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments137 = array();
$renderChildrenClosure138 = function() use ($renderingContext, $self) {
$output139 = '';

$output139 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments140 = array();
// Rendering Boolean node
$arguments140['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments140['id'] = 'username';
$arguments140['type'] = 'text';
$arguments140['placeholder'] = 'Username';
$arguments140['class'] = 'neos-span12';
$arguments140['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
// Rendering Array
$array141 = array();
$array141['autofocus'] = 'autofocus';
$arguments140['additionalAttributes'] = $array141;
$arguments140['data'] = NULL;
$arguments140['value'] = NULL;
$arguments140['property'] = NULL;
$arguments140['disabled'] = NULL;
$arguments140['maxlength'] = NULL;
$arguments140['readonly'] = NULL;
$arguments140['size'] = NULL;
$arguments140['autofocus'] = NULL;
$arguments140['errorClass'] = 'f3-form-error';
$arguments140['dir'] = NULL;
$arguments140['lang'] = NULL;
$arguments140['style'] = NULL;
$arguments140['title'] = NULL;
$arguments140['accesskey'] = NULL;
$arguments140['tabindex'] = NULL;
$arguments140['onclick'] = NULL;
$renderChildrenClosure142 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper143 = $self->getViewHelper('$viewHelper143', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper143->setArguments($arguments140);
$viewHelper143->setRenderingContext($renderingContext);
$viewHelper143->setRenderChildrenClosure($renderChildrenClosure142);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output139 .= $viewHelper143->initializeArgumentsAndRender();

$output139 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments144 = array();
// Rendering Boolean node
$arguments144['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments144['id'] = 'password';
$arguments144['type'] = 'password';
$arguments144['placeholder'] = 'Password';
$arguments144['class'] = 'neos-span12';
$arguments144['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
$arguments144['additionalAttributes'] = NULL;
$arguments144['data'] = NULL;
$arguments144['value'] = NULL;
$arguments144['property'] = NULL;
$arguments144['disabled'] = NULL;
$arguments144['maxlength'] = NULL;
$arguments144['readonly'] = NULL;
$arguments144['size'] = NULL;
$arguments144['autofocus'] = NULL;
$arguments144['errorClass'] = 'f3-form-error';
$arguments144['dir'] = NULL;
$arguments144['lang'] = NULL;
$arguments144['style'] = NULL;
$arguments144['title'] = NULL;
$arguments144['accesskey'] = NULL;
$arguments144['tabindex'] = NULL;
$arguments144['onclick'] = NULL;
$renderChildrenClosure145 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper146 = $self->getViewHelper('$viewHelper146', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper146->setArguments($arguments144);
$viewHelper146->setRenderingContext($renderingContext);
$viewHelper146->setRenderChildrenClosure($renderChildrenClosure145);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output139 .= $viewHelper146->initializeArgumentsAndRender();

$output139 .= '
									</div>
								';
return $output139;
};
$viewHelper147 = $self->getViewHelper('$viewHelper147', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper147->setArguments($arguments137);
$viewHelper147->setRenderingContext($renderingContext);
$viewHelper147->setRenderChildrenClosure($renderChildrenClosure138);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output125 .= $viewHelper147->initializeArgumentsAndRender();

$output125 .= '
							';
return $output125;
};
$arguments123['__thenClosure'] = function() use ($renderingContext, $self) {
$output148 = '';

$output148 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments149 = array();
// Rendering Boolean node
$arguments149['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments149['id'] = 'username';
$arguments149['type'] = 'text';
$arguments149['placeholder'] = 'Username';
$arguments149['class'] = 'neos-span12';
$arguments149['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
$arguments149['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'username', $renderingContext);
$arguments149['additionalAttributes'] = NULL;
$arguments149['data'] = NULL;
$arguments149['property'] = NULL;
$arguments149['disabled'] = NULL;
$arguments149['maxlength'] = NULL;
$arguments149['readonly'] = NULL;
$arguments149['size'] = NULL;
$arguments149['autofocus'] = NULL;
$arguments149['errorClass'] = 'f3-form-error';
$arguments149['dir'] = NULL;
$arguments149['lang'] = NULL;
$arguments149['style'] = NULL;
$arguments149['title'] = NULL;
$arguments149['accesskey'] = NULL;
$arguments149['tabindex'] = NULL;
$arguments149['onclick'] = NULL;
$renderChildrenClosure150 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper151 = $self->getViewHelper('$viewHelper151', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper151->setArguments($arguments149);
$viewHelper151->setRenderingContext($renderingContext);
$viewHelper151->setRenderChildrenClosure($renderChildrenClosure150);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output148 .= $viewHelper151->initializeArgumentsAndRender();

$output148 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments152 = array();
// Rendering Boolean node
$arguments152['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments152['id'] = 'password';
$arguments152['type'] = 'password';
$arguments152['placeholder'] = 'Password';
$arguments152['class'] = 'neos-span12';
$arguments152['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
// Rendering Array
$array153 = array();
$array153['autofocus'] = 'autofocus';
$arguments152['additionalAttributes'] = $array153;
$arguments152['data'] = NULL;
$arguments152['value'] = NULL;
$arguments152['property'] = NULL;
$arguments152['disabled'] = NULL;
$arguments152['maxlength'] = NULL;
$arguments152['readonly'] = NULL;
$arguments152['size'] = NULL;
$arguments152['autofocus'] = NULL;
$arguments152['errorClass'] = 'f3-form-error';
$arguments152['dir'] = NULL;
$arguments152['lang'] = NULL;
$arguments152['style'] = NULL;
$arguments152['title'] = NULL;
$arguments152['accesskey'] = NULL;
$arguments152['tabindex'] = NULL;
$arguments152['onclick'] = NULL;
$renderChildrenClosure154 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper155 = $self->getViewHelper('$viewHelper155', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper155->setArguments($arguments152);
$viewHelper155->setRenderingContext($renderingContext);
$viewHelper155->setRenderChildrenClosure($renderChildrenClosure154);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output148 .= $viewHelper155->initializeArgumentsAndRender();

$output148 .= '
									</div>
								';
return $output148;
};
$arguments123['__elseClosure'] = function() use ($renderingContext, $self) {
$output156 = '';

$output156 .= '
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments157 = array();
// Rendering Boolean node
$arguments157['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments157['id'] = 'username';
$arguments157['type'] = 'text';
$arguments157['placeholder'] = 'Username';
$arguments157['class'] = 'neos-span12';
$arguments157['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]';
// Rendering Array
$array158 = array();
$array158['autofocus'] = 'autofocus';
$arguments157['additionalAttributes'] = $array158;
$arguments157['data'] = NULL;
$arguments157['value'] = NULL;
$arguments157['property'] = NULL;
$arguments157['disabled'] = NULL;
$arguments157['maxlength'] = NULL;
$arguments157['readonly'] = NULL;
$arguments157['size'] = NULL;
$arguments157['autofocus'] = NULL;
$arguments157['errorClass'] = 'f3-form-error';
$arguments157['dir'] = NULL;
$arguments157['lang'] = NULL;
$arguments157['style'] = NULL;
$arguments157['title'] = NULL;
$arguments157['accesskey'] = NULL;
$arguments157['tabindex'] = NULL;
$arguments157['onclick'] = NULL;
$renderChildrenClosure159 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper160 = $self->getViewHelper('$viewHelper160', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper160->setArguments($arguments157);
$viewHelper160->setRenderingContext($renderingContext);
$viewHelper160->setRenderChildrenClosure($renderChildrenClosure159);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output156 .= $viewHelper160->initializeArgumentsAndRender();

$output156 .= '
									</div>
									<div class="neos-controls">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper
$arguments161 = array();
// Rendering Boolean node
$arguments161['required'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('required');
$arguments161['id'] = 'password';
$arguments161['type'] = 'password';
$arguments161['placeholder'] = 'Password';
$arguments161['class'] = 'neos-span12';
$arguments161['name'] = '__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]';
$arguments161['additionalAttributes'] = NULL;
$arguments161['data'] = NULL;
$arguments161['value'] = NULL;
$arguments161['property'] = NULL;
$arguments161['disabled'] = NULL;
$arguments161['maxlength'] = NULL;
$arguments161['readonly'] = NULL;
$arguments161['size'] = NULL;
$arguments161['autofocus'] = NULL;
$arguments161['errorClass'] = 'f3-form-error';
$arguments161['dir'] = NULL;
$arguments161['lang'] = NULL;
$arguments161['style'] = NULL;
$arguments161['title'] = NULL;
$arguments161['accesskey'] = NULL;
$arguments161['tabindex'] = NULL;
$arguments161['onclick'] = NULL;
$renderChildrenClosure162 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper163 = $self->getViewHelper('$viewHelper163', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper');
$viewHelper163->setArguments($arguments161);
$viewHelper163->setRenderingContext($renderingContext);
$viewHelper163->setRenderChildrenClosure($renderChildrenClosure162);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper

$output156 .= $viewHelper163->initializeArgumentsAndRender();

$output156 .= '
									</div>
								';
return $output156;
};
$viewHelper164 = $self->getViewHelper('$viewHelper164', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper164->setArguments($arguments123);
$viewHelper164->setRenderingContext($renderingContext);
$viewHelper164->setRenderChildrenClosure($renderChildrenClosure124);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output119 .= $viewHelper164->initializeArgumentsAndRender();

$output119 .= '
						<div class="neos-actions">
							<!-- Forgot password link will be here -->
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
$arguments165 = array();
$arguments165['type'] = 'submit';
$arguments165['class'] = 'neos-span5 neos-pull-right neos-button neos-login-btn';
$arguments165['additionalAttributes'] = NULL;
$arguments165['data'] = NULL;
$arguments165['name'] = NULL;
$arguments165['value'] = NULL;
$arguments165['property'] = NULL;
$arguments165['autofocus'] = NULL;
$arguments165['disabled'] = NULL;
$arguments165['form'] = NULL;
$arguments165['formaction'] = NULL;
$arguments165['formenctype'] = NULL;
$arguments165['formmethod'] = NULL;
$arguments165['formnovalidate'] = NULL;
$arguments165['formtarget'] = NULL;
$arguments165['dir'] = NULL;
$arguments165['id'] = NULL;
$arguments165['lang'] = NULL;
$arguments165['style'] = NULL;
$arguments165['title'] = NULL;
$arguments165['accesskey'] = NULL;
$arguments165['tabindex'] = NULL;
$arguments165['onclick'] = NULL;
$renderChildrenClosure166 = function() use ($renderingContext, $self) {
return '
								Login
							';
};
$viewHelper167 = $self->getViewHelper('$viewHelper167', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper');
$viewHelper167->setArguments($arguments165);
$viewHelper167->setRenderingContext($renderingContext);
$viewHelper167->setRenderChildrenClosure($renderChildrenClosure166);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper

$output119 .= $viewHelper167->initializeArgumentsAndRender();

$output119 .= '
							<button class="neos-span5 neos-pull-right neos-button neos-login-btn neos-disabled neos-hidden">
								Authenticating<span class="neos-ellipsis"></span>
							</button>
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper
$arguments168 = array();
$arguments168['as'] = 'flashMessages';
$arguments168['additionalAttributes'] = NULL;
$arguments168['data'] = NULL;
$arguments168['severity'] = NULL;
$arguments168['class'] = NULL;
$arguments168['dir'] = NULL;
$arguments168['id'] = NULL;
$arguments168['lang'] = NULL;
$arguments168['style'] = NULL;
$arguments168['title'] = NULL;
$arguments168['accesskey'] = NULL;
$arguments168['tabindex'] = NULL;
$arguments168['onclick'] = NULL;
$renderChildrenClosure169 = function() use ($renderingContext, $self) {
$output170 = '';

$output170 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments171 = array();
$arguments171['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessages', $renderingContext);
$arguments171['as'] = 'flashMessage';
$arguments171['key'] = '';
$arguments171['reverse'] = false;
$arguments171['iteration'] = NULL;
$renderChildrenClosure172 = function() use ($renderingContext, $self) {
$output173 = '';

$output173 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments174 = array();
// Rendering Boolean node
$arguments174['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'OK');
$arguments174['then'] = NULL;
$arguments174['else'] = NULL;
$renderChildrenClosure175 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-success">
									';
};
$viewHelper176 = $self->getViewHelper('$viewHelper176', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper176->setArguments($arguments174);
$viewHelper176->setRenderingContext($renderingContext);
$viewHelper176->setRenderChildrenClosure($renderChildrenClosure175);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output173 .= $viewHelper176->initializeArgumentsAndRender();

$output173 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments177 = array();
// Rendering Boolean node
$arguments177['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Notice');
$arguments177['then'] = NULL;
$arguments177['else'] = NULL;
$renderChildrenClosure178 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-notice">
									';
};
$viewHelper179 = $self->getViewHelper('$viewHelper179', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper179->setArguments($arguments177);
$viewHelper179->setRenderingContext($renderingContext);
$viewHelper179->setRenderChildrenClosure($renderChildrenClosure178);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output173 .= $viewHelper179->initializeArgumentsAndRender();

$output173 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments180 = array();
// Rendering Boolean node
$arguments180['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Warning');
$arguments180['then'] = NULL;
$arguments180['else'] = NULL;
$renderChildrenClosure181 = function() use ($renderingContext, $self) {
return '
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-warning">
									';
};
$viewHelper182 = $self->getViewHelper('$viewHelper182', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper182->setArguments($arguments180);
$viewHelper182->setRenderingContext($renderingContext);
$viewHelper182->setRenderChildrenClosure($renderChildrenClosure181);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output173 .= $viewHelper182->initializeArgumentsAndRender();

$output173 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments183 = array();
// Rendering Boolean node
$arguments183['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.severity', $renderingContext), 'Error');
$arguments183['then'] = NULL;
$arguments183['else'] = NULL;
$renderChildrenClosure184 = function() use ($renderingContext, $self) {
$output185 = '';

$output185 .= '
										<script>
											$(function() {
												$(\'fieldset\').effect(\'shake\', ';

$output185 .= '{times: 1}';

$output185 .= ', 60);
											});
										</script>
										<div class="neos-tooltip neos-bottom neos-in neos-tooltip-error">
									';
return $output185;
};
$viewHelper186 = $self->getViewHelper('$viewHelper186', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper186->setArguments($arguments183);
$viewHelper186->setRenderingContext($renderingContext);
$viewHelper186->setRenderChildrenClosure($renderChildrenClosure184);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output173 .= $viewHelper186->initializeArgumentsAndRender();

$output173 .= '
										<div class="neos-tooltip-arrow"></div>
										<div class="neos-tooltip-inner">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments187 = array();
$arguments187['value'] = NULL;
$renderChildrenClosure188 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'flashMessage.message', $renderingContext);
};
$viewHelper189 = $self->getViewHelper('$viewHelper189', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper189->setArguments($arguments187);
$viewHelper189->setRenderingContext($renderingContext);
$viewHelper189->setRenderChildrenClosure($renderChildrenClosure188);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output173 .= $viewHelper189->initializeArgumentsAndRender();

$output173 .= '</div>
									</div>
								';
return $output173;
};

$output170 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments171, $renderChildrenClosure172, $renderingContext);

$output170 .= '
							';
return $output170;
};
$viewHelper190 = $self->getViewHelper('$viewHelper190', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper');
$viewHelper190->setArguments($arguments168);
$viewHelper190->setRenderingContext($renderingContext);
$viewHelper190->setRenderChildrenClosure($renderChildrenClosure169);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FlashMessagesViewHelper

$output119 .= $viewHelper190->initializeArgumentsAndRender();

$output119 .= '
						</div>
					</fieldset>
				';
return $output119;
};
$viewHelper191 = $self->getViewHelper('$viewHelper191', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper191->setArguments($arguments117);
$viewHelper191->setRenderingContext($renderingContext);
$viewHelper191->setRenderChildrenClosure($renderChildrenClosure118);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output113 .= $viewHelper191->initializeArgumentsAndRender();

$output113 .= '
			</div>
		</div>
		<div id="neos-login-footer">
			<p>
				<a href="http://neos.typo3.org" target="_blank">TYPO3 Neos</a> – © 2006-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\DateViewHelper
$arguments192 = array();
$arguments192['format'] = 'Y';
$arguments192['date'] = 'now';
$arguments192['forceLocale'] = NULL;
$arguments192['localeFormatType'] = NULL;
$arguments192['localeFormatLength'] = NULL;
$arguments192['cldrFormat'] = NULL;
$renderChildrenClosure193 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper194 = $self->getViewHelper('$viewHelper194', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\DateViewHelper');
$viewHelper194->setArguments($arguments192);
$viewHelper194->setRenderingContext($renderingContext);
$viewHelper194->setRenderChildrenClosure($renderChildrenClosure193);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\DateViewHelper

$output113 .= $viewHelper194->initializeArgumentsAndRender();

$output113 .= '
				This is free software, licensed under GPL3 or higher, and you are welcome to redistribute it under certain conditions; <a href="http://typo3.org/licenses" target="_blank">click for details.</a>
				TYPO3 Neos comes with ABSOLUTELY NO WARRANTY; <a href="http://typo3.org/licenses" target="_blank">click for details.</a>
				See <a href="http://neos.typo3.org" target="_blank">neos.typo3.org</a> for more details.
				Obstructing the appearance of this notice is prohibited by law.
			</p>
		</div>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments195 = array();
$arguments195['path'] = '2/js/bootstrap.min.js';
$arguments195['package'] = 'TYPO3.Twitter.Bootstrap';
$arguments195['resource'] = NULL;
$arguments195['localize'] = true;
$renderChildrenClosure196 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper197 = $self->getViewHelper('$viewHelper197', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper197->setArguments($arguments195);
$viewHelper197->setRenderingContext($renderingContext);
$viewHelper197->setRenderChildrenClosure($renderChildrenClosure196);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output113 .= $viewHelper197->initializeArgumentsAndRender();

$output113 .= '"></script>
		<script>
			if ($(\'#username\').val()) {
				$(\'#password\').focus();
			}
			$(\'form\').on(\'submit\', function() {
				$(\'.neos-login-btn\').toggleClass(\'neos-hidden\');
			});
			try {
				$(\'form[name="login"] input[name="lastVisitedNode"]\').val(sessionStorage.getItem(\'TYPO3.Neos.lastVisitedNode\'));
			} catch(e) {}
		</script>
	</body>
';
return $output113;
};

$output95 .= '';

$output95 .= '
';

return $output95;
}


}
#0             71323     