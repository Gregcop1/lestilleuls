<?php class FluidCache_Standalone_template_file_Index_7ed460cab466873c37b330b110bf76d9b208561a extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	<title>TYPO3 Neos Error</title>
	<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments1 = array();
$arguments1['path'] = 'Styles/Error.css';
$arguments1['package'] = 'TYPO3.Neos';
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
';

return $output0;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output4 = '';

$output4 .= '
	<body class="neos">
		<div class="neos-error-screen">
			<div class="neos-message-icon">
				<i class="icon-warning-sign"></i>
			</div>
			<h1>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments5 = array();
$arguments5['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errorTitle', $renderingContext);
$arguments5['keepQuotes'] = false;
$arguments5['encoding'] = 'UTF-8';
$arguments5['doubleEncode'] = true;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
return NULL;
};
$value7 = ($arguments5['value'] !== NULL ? $arguments5['value'] : $renderChildrenClosure6());

$output4 .= !is_string($value7) && !(is_object($value7) && method_exists($value7, '__toString')) ? $value7 : htmlspecialchars($value7, ($arguments5['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments5['encoding'], $arguments5['doubleEncode']);

$output4 .= '</h1>
			<p>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments8 = array();
$arguments8['value'] = NULL;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errorDescription', $renderingContext);
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output4 .= $viewHelper10->initializeArgumentsAndRender();

$output4 .= '</p>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments11 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments12 = array();
$arguments12['value'] = NULL;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'setupMessage', $renderingContext);
};
$viewHelper14 = $self->getViewHelper('$viewHelper14', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper14->setArguments($arguments12);
$viewHelper14->setRenderingContext($renderingContext);
$viewHelper14->setRenderChildrenClosure($renderChildrenClosure13);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments11['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper14->initializeArgumentsAndRender());
$arguments11['then'] = NULL;
$arguments11['else'] = NULL;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
$output16 = '';

$output16 .= '
				<p><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments17 = array();
$arguments17['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'setupMessage', $renderingContext);
$arguments17['keepQuotes'] = false;
$arguments17['encoding'] = 'UTF-8';
$arguments17['doubleEncode'] = true;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return NULL;
};
$value19 = ($arguments17['value'] !== NULL ? $arguments17['value'] : $renderChildrenClosure18());

$output16 .= !is_string($value19) && !(is_object($value19) && method_exists($value19, '__toString')) ? $value19 : htmlspecialchars($value19, ($arguments17['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments17['encoding'], $arguments17['doubleEncode']);

$output16 .= '</strong></p>
				<p><a href="/setup" class="neos-button neos-button-primary">Go to setup</a></p>
			';
return $output16;
};
$viewHelper20 = $self->getViewHelper('$viewHelper20', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper20->setArguments($arguments11);
$viewHelper20->setRenderingContext($renderingContext);
$viewHelper20->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output4 .= $viewHelper20->initializeArgumentsAndRender();

$output4 .= '
		</div>
	</body>
';

return $output4;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output21 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments22 = array();
$arguments22['name'] = 'Default';
$renderChildrenClosure23 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper24 = $self->getViewHelper('$viewHelper24', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper24->setArguments($arguments22);
$viewHelper24->setRenderingContext($renderingContext);
$viewHelper24->setRenderChildrenClosure($renderChildrenClosure23);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output21 .= $viewHelper24->initializeArgumentsAndRender();

$output21 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments25 = array();
$arguments25['name'] = 'head';
$renderChildrenClosure26 = function() use ($renderingContext, $self) {
$output27 = '';

$output27 .= '
	<title>TYPO3 Neos Error</title>
	<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments28 = array();
$arguments28['path'] = 'Styles/Error.css';
$arguments28['package'] = 'TYPO3.Neos';
$arguments28['resource'] = NULL;
$arguments28['localize'] = true;
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper30->setArguments($arguments28);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure29);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output27 .= $viewHelper30->initializeArgumentsAndRender();

$output27 .= '" />
';
return $output27;
};

$output21 .= '';

$output21 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments31 = array();
$arguments31['name'] = 'body';
$renderChildrenClosure32 = function() use ($renderingContext, $self) {
$output33 = '';

$output33 .= '
	<body class="neos">
		<div class="neos-error-screen">
			<div class="neos-message-icon">
				<i class="icon-warning-sign"></i>
			</div>
			<h1>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments34 = array();
$arguments34['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errorTitle', $renderingContext);
$arguments34['keepQuotes'] = false;
$arguments34['encoding'] = 'UTF-8';
$arguments34['doubleEncode'] = true;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
return NULL;
};
$value36 = ($arguments34['value'] !== NULL ? $arguments34['value'] : $renderChildrenClosure35());

$output33 .= !is_string($value36) && !(is_object($value36) && method_exists($value36, '__toString')) ? $value36 : htmlspecialchars($value36, ($arguments34['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments34['encoding'], $arguments34['doubleEncode']);

$output33 .= '</h1>
			<p>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments37 = array();
$arguments37['value'] = NULL;
$renderChildrenClosure38 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'errorDescription', $renderingContext);
};
$viewHelper39 = $self->getViewHelper('$viewHelper39', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper39->setArguments($arguments37);
$viewHelper39->setRenderingContext($renderingContext);
$viewHelper39->setRenderChildrenClosure($renderChildrenClosure38);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output33 .= $viewHelper39->initializeArgumentsAndRender();

$output33 .= '</p>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments40 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments41 = array();
$arguments41['value'] = NULL;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'setupMessage', $renderingContext);
};
$viewHelper43 = $self->getViewHelper('$viewHelper43', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper43->setArguments($arguments41);
$viewHelper43->setRenderingContext($renderingContext);
$viewHelper43->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments40['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper43->initializeArgumentsAndRender());
$arguments40['then'] = NULL;
$arguments40['else'] = NULL;
$renderChildrenClosure44 = function() use ($renderingContext, $self) {
$output45 = '';

$output45 .= '
				<p><strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments46 = array();
$arguments46['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'setupMessage', $renderingContext);
$arguments46['keepQuotes'] = false;
$arguments46['encoding'] = 'UTF-8';
$arguments46['doubleEncode'] = true;
$renderChildrenClosure47 = function() use ($renderingContext, $self) {
return NULL;
};
$value48 = ($arguments46['value'] !== NULL ? $arguments46['value'] : $renderChildrenClosure47());

$output45 .= !is_string($value48) && !(is_object($value48) && method_exists($value48, '__toString')) ? $value48 : htmlspecialchars($value48, ($arguments46['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments46['encoding'], $arguments46['doubleEncode']);

$output45 .= '</strong></p>
				<p><a href="/setup" class="neos-button neos-button-primary">Go to setup</a></p>
			';
return $output45;
};
$viewHelper49 = $self->getViewHelper('$viewHelper49', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper49->setArguments($arguments40);
$viewHelper49->setRenderingContext($renderingContext);
$viewHelper49->setRenderChildrenClosure($renderChildrenClosure44);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output33 .= $viewHelper49->initializeArgumentsAndRender();

$output33 .= '
		</div>
	</body>
';
return $output33;
};

$output21 .= '';

return $output21;
}


}
#0             12776     