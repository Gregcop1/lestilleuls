<?php class FluidCache_Standalone_template_file_Default_2cd5d4dd652f386faa574733fd6a73a633a56b5e extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
 * section stylesheets
 */
public function section_8b563540d8e2cf00c7aab74292484dfc45d600e7(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return '
		<!-- Put your stylesheet inclusions here, they will be included in your website by TypoScript -->
	';
}
/**
 * section headScripts
 */
public function section_b614b45fc3daabe190ca47a686a6154517c3cbf5(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
	';
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments1 = array();
$arguments1['value'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments4 = array();
$arguments4['value'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output0 .= $viewHelper6->initializeArgumentsAndRender();

$output0 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments7 = array();
$arguments7['value'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper9 = $self->getViewHelper('$viewHelper9', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper9->setArguments($arguments7);
$viewHelper9->setRenderingContext($renderingContext);
$viewHelper9->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output0 .= $viewHelper9->initializeArgumentsAndRender();

$output0 .= '
	</div>
';

return $output0;
}
/**
 * section bodyScripts
 */
public function section_1c1dd0596640090d96e10e9c178a187288240ef4(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return '
	<!-- Put your scripts inclusions for the end of the body here, they will be included in your website by TypoScript -->
';
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output10 = '';

$output10 .= '<!DOCTYPE html>


<html>
<head>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments11 = array();
$arguments11['name'] = 'stylesheets';
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return '
		<!-- Put your stylesheet inclusions here, they will be included in your website by TypoScript -->
	';
};

$output10 .= '';

$output10 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments13 = array();
$arguments13['name'] = 'headScripts';
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
return '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
	';
};

$output10 .= '';

$output10 .= '
</head>
<body>
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments15 = array();
$arguments15['name'] = 'body';
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
$output17 = '';

$output17 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments18 = array();
$arguments18['value'] = NULL;
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper20 = $self->getViewHelper('$viewHelper20', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper20->setArguments($arguments18);
$viewHelper20->setRenderingContext($renderingContext);
$viewHelper20->setRenderChildrenClosure($renderChildrenClosure19);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output17 .= $viewHelper20->initializeArgumentsAndRender();

$output17 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments21 = array();
$arguments21['value'] = NULL;
$renderChildrenClosure22 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper23 = $self->getViewHelper('$viewHelper23', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper23->setArguments($arguments21);
$viewHelper23->setRenderingContext($renderingContext);
$viewHelper23->setRenderChildrenClosure($renderChildrenClosure22);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output17 .= $viewHelper23->initializeArgumentsAndRender();

$output17 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments24 = array();
$arguments24['value'] = NULL;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper26->setArguments($arguments24);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output17 .= $viewHelper26->initializeArgumentsAndRender();

$output17 .= '
	</div>
';
return $output17;
};

$output10 .= '';

$output10 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments27 = array();
$arguments27['name'] = 'bodyScripts';
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
return '
	<!-- Put your scripts inclusions for the end of the body here, they will be included in your website by TypoScript -->
';
};

$output10 .= '';

$output10 .= '
</body>
</html>
';

return $output10;
}


}
#0             8313      