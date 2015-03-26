<?php class FluidCache_Standalone_template_file_Default_9291df9c849885237ae74cd882f94587ba12a468 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
$output0 = '';

$output0 .= '
		<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments1 = array();
$arguments1['path'] = 'css/main.css';
$arguments1['package'] = 'Gc.Tilleuls';
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

$output0 .= '" media="all" />
	';

return $output0;
}
/**
 * section headScripts
 */
public function section_b614b45fc3daabe190ca47a686a6154517c3cbf5(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output4 = '';

$output4 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments5 = array();
$arguments5['path'] = 'js/components/modernizr.custom.js';
$arguments5['package'] = 'Gc.Tilleuls';
$arguments5['resource'] = NULL;
$arguments5['localize'] = true;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper7 = $self->getViewHelper('$viewHelper7', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper7->setArguments($arguments5);
$viewHelper7->setRenderingContext($renderingContext);
$viewHelper7->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output4 .= $viewHelper7->initializeArgumentsAndRender();

$output4 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments8 = array();
$arguments8['path'] = 'js/components/jquery.min.js';
$arguments8['package'] = 'Gc.Tilleuls';
$arguments8['resource'] = NULL;
$arguments8['localize'] = true;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output4 .= $viewHelper10->initializeArgumentsAndRender();

$output4 .= '" type="text/javascript"></script>
	';

return $output4;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output11 = '';

$output11 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments12 = array();
$arguments12['value'] = NULL;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper14 = $self->getViewHelper('$viewHelper14', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper14->setArguments($arguments12);
$viewHelper14->setRenderingContext($renderingContext);
$viewHelper14->setRenderChildrenClosure($renderChildrenClosure13);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper14->initializeArgumentsAndRender();

$output11 .= '
	</nav>
	<div class="mainContent content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments15 = array();
$arguments15['value'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper17 = $self->getViewHelper('$viewHelper17', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper17->setArguments($arguments15);
$viewHelper17->setRenderingContext($renderingContext);
$viewHelper17->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper17->initializeArgumentsAndRender();

$output11 .= '
	</div>
';

return $output11;
}
/**
 * section bodyScripts
 */
public function section_1c1dd0596640090d96e10e9c178a187288240ef4(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output18 = '';

$output18 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments19 = array();
$arguments19['path'] = 'js/base.js';
$arguments19['package'] = 'Gc.Tilleuls';
$arguments19['resource'] = NULL;
$arguments19['localize'] = true;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper21 = $self->getViewHelper('$viewHelper21', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper21->setArguments($arguments19);
$viewHelper21->setRenderingContext($renderingContext);
$viewHelper21->setRenderChildrenClosure($renderChildrenClosure20);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output18 .= $viewHelper21->initializeArgumentsAndRender();

$output18 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments22 = array();
$arguments22['path'] = 'js/main.js';
$arguments22['package'] = 'Gc.Tilleuls';
$arguments22['resource'] = NULL;
$arguments22['localize'] = true;
$renderChildrenClosure23 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper24 = $self->getViewHelper('$viewHelper24', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper24->setArguments($arguments22);
$viewHelper24->setRenderingContext($renderingContext);
$viewHelper24->setRenderChildrenClosure($renderChildrenClosure23);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output18 .= $viewHelper24->initializeArgumentsAndRender();

$output18 .= '" type="text/javascript"></script>
';

return $output18;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output25 = '';

$output25 .= '<!DOCTYPE html>


<html>
<head>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments26 = array();
$arguments26['name'] = 'stylesheets';
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
$output28 = '';

$output28 .= '
		<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments29 = array();
$arguments29['path'] = 'css/main.css';
$arguments29['package'] = 'Gc.Tilleuls';
$arguments29['resource'] = NULL;
$arguments29['localize'] = true;
$renderChildrenClosure30 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper31 = $self->getViewHelper('$viewHelper31', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper31->setArguments($arguments29);
$viewHelper31->setRenderingContext($renderingContext);
$viewHelper31->setRenderChildrenClosure($renderChildrenClosure30);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output28 .= $viewHelper31->initializeArgumentsAndRender();

$output28 .= '" media="all" />
	';
return $output28;
};

$output25 .= '';

$output25 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments32 = array();
$arguments32['name'] = 'headScripts';
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
$output34 = '';

$output34 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments35 = array();
$arguments35['path'] = 'js/components/modernizr.custom.js';
$arguments35['package'] = 'Gc.Tilleuls';
$arguments35['resource'] = NULL;
$arguments35['localize'] = true;
$renderChildrenClosure36 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper37 = $self->getViewHelper('$viewHelper37', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper37->setArguments($arguments35);
$viewHelper37->setRenderingContext($renderingContext);
$viewHelper37->setRenderChildrenClosure($renderChildrenClosure36);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output34 .= $viewHelper37->initializeArgumentsAndRender();

$output34 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments38 = array();
$arguments38['path'] = 'js/components/jquery.min.js';
$arguments38['package'] = 'Gc.Tilleuls';
$arguments38['resource'] = NULL;
$arguments38['localize'] = true;
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper40 = $self->getViewHelper('$viewHelper40', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper40->setArguments($arguments38);
$viewHelper40->setRenderingContext($renderingContext);
$viewHelper40->setRenderChildrenClosure($renderChildrenClosure39);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output34 .= $viewHelper40->initializeArgumentsAndRender();

$output34 .= '" type="text/javascript"></script>
	';
return $output34;
};

$output25 .= '';

$output25 .= '
</head>
<body>
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments41 = array();
$arguments41['name'] = 'body';
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
$output43 = '';

$output43 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments44 = array();
$arguments44['value'] = NULL;
$renderChildrenClosure45 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper46 = $self->getViewHelper('$viewHelper46', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper46->setArguments($arguments44);
$viewHelper46->setRenderingContext($renderingContext);
$viewHelper46->setRenderChildrenClosure($renderChildrenClosure45);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output43 .= $viewHelper46->initializeArgumentsAndRender();

$output43 .= '
	</nav>
	<div class="mainContent content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments47 = array();
$arguments47['value'] = NULL;
$renderChildrenClosure48 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper49 = $self->getViewHelper('$viewHelper49', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper49->setArguments($arguments47);
$viewHelper49->setRenderingContext($renderingContext);
$viewHelper49->setRenderChildrenClosure($renderChildrenClosure48);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output43 .= $viewHelper49->initializeArgumentsAndRender();

$output43 .= '
	</div>
';
return $output43;
};

$output25 .= '';

$output25 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments50 = array();
$arguments50['name'] = 'bodyScripts';
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
$output52 = '';

$output52 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments53 = array();
$arguments53['path'] = 'js/base.js';
$arguments53['package'] = 'Gc.Tilleuls';
$arguments53['resource'] = NULL;
$arguments53['localize'] = true;
$renderChildrenClosure54 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper55 = $self->getViewHelper('$viewHelper55', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper55->setArguments($arguments53);
$viewHelper55->setRenderingContext($renderingContext);
$viewHelper55->setRenderChildrenClosure($renderChildrenClosure54);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output52 .= $viewHelper55->initializeArgumentsAndRender();

$output52 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments56 = array();
$arguments56['path'] = 'js/main.js';
$arguments56['package'] = 'Gc.Tilleuls';
$arguments56['resource'] = NULL;
$arguments56['localize'] = true;
$renderChildrenClosure57 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper58 = $self->getViewHelper('$viewHelper58', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper58->setArguments($arguments56);
$viewHelper58->setRenderingContext($renderingContext);
$viewHelper58->setRenderChildrenClosure($renderChildrenClosure57);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output52 .= $viewHelper58->initializeArgumentsAndRender();

$output52 .= '" type="text/javascript"></script>
';
return $output52;
};

$output25 .= '';

$output25 .= '
</body>
</html>
';

return $output25;
}


}
#0             14663     