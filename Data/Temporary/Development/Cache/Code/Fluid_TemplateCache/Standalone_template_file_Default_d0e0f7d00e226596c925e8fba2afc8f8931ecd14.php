<?php class FluidCache_Standalone_template_file_Default_d0e0f7d00e226596c925e8fba2afc8f8931ecd14 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
$output0 = '';

$output0 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments1 = array();
$arguments1['path'] = 'js/components/modernizr.custom.js';
$arguments1['package'] = 'GC.Carvin';
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

$output0 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments4 = array();
$arguments4['path'] = 'js/components/jquery.min.js';
$arguments4['package'] = 'GC.Carvin';
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

$output0 .= '" type="text/javascript"></script>
	';

return $output0;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output7 = '';

$output7 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments8 = array();
$arguments8['value'] = NULL;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output7 .= $viewHelper10->initializeArgumentsAndRender();

$output7 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments11 = array();
$arguments11['value'] = NULL;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output7 .= $viewHelper13->initializeArgumentsAndRender();

$output7 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments14 = array();
$arguments14['value'] = NULL;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper16 = $self->getViewHelper('$viewHelper16', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper16->setArguments($arguments14);
$viewHelper16->setRenderingContext($renderingContext);
$viewHelper16->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output7 .= $viewHelper16->initializeArgumentsAndRender();

$output7 .= '
	</div>
';

return $output7;
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
$output17 = '';

$output17 .= '<!DOCTYPE html>


<html>
<head>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments18 = array();
$arguments18['name'] = 'stylesheets';
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
return '
		<!-- Put your stylesheet inclusions here, they will be included in your website by TypoScript -->
	';
};

$output17 .= '';

$output17 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments20 = array();
$arguments20['name'] = 'headScripts';
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
$output22 = '';

$output22 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments23 = array();
$arguments23['path'] = 'js/components/modernizr.custom.js';
$arguments23['package'] = 'GC.Carvin';
$arguments23['resource'] = NULL;
$arguments23['localize'] = true;
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper25->setArguments($arguments23);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output22 .= $viewHelper25->initializeArgumentsAndRender();

$output22 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments26 = array();
$arguments26['path'] = 'js/components/jquery.min.js';
$arguments26['package'] = 'GC.Carvin';
$arguments26['resource'] = NULL;
$arguments26['localize'] = true;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper28->setArguments($arguments26);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output22 .= $viewHelper28->initializeArgumentsAndRender();

$output22 .= '" type="text/javascript"></script>
	';
return $output22;
};

$output17 .= '';

$output17 .= '
</head>
<body>
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments29 = array();
$arguments29['name'] = 'body';
$renderChildrenClosure30 = function() use ($renderingContext, $self) {
$output31 = '';

$output31 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments32 = array();
$arguments32['value'] = NULL;
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output31 .= $viewHelper34->initializeArgumentsAndRender();

$output31 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments35 = array();
$arguments35['value'] = NULL;
$renderChildrenClosure36 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper37 = $self->getViewHelper('$viewHelper37', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper37->setArguments($arguments35);
$viewHelper37->setRenderingContext($renderingContext);
$viewHelper37->setRenderChildrenClosure($renderChildrenClosure36);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output31 .= $viewHelper37->initializeArgumentsAndRender();

$output31 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments38 = array();
$arguments38['value'] = NULL;
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper40 = $self->getViewHelper('$viewHelper40', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper40->setArguments($arguments38);
$viewHelper40->setRenderingContext($renderingContext);
$viewHelper40->setRenderChildrenClosure($renderChildrenClosure39);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output31 .= $viewHelper40->initializeArgumentsAndRender();

$output31 .= '
	</div>
';
return $output31;
};

$output17 .= '';

$output17 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments41 = array();
$arguments41['name'] = 'bodyScripts';
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return '
	<!-- Put your scripts inclusions for the end of the body here, they will be included in your website by TypoScript -->
';
};

$output17 .= '';

$output17 .= '
</body>
</html>
';

return $output17;
}


}
#0             11712     