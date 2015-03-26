<?php class FluidCache_Standalone_template_file_NeosBackendHeaderData_d300a48902df8634e5e3c9aae09ea5a09af6c2cb extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper
$arguments1 = array();
$arguments1['resource'] = 'TYPO3_Neos_Backend_GeneralAccess';
$arguments1['then'] = NULL;
$arguments1['else'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
	<script type="text/javascript">
				// TODO Get rid of those global variables
			';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper
$arguments4 = array();
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper

$output3 .= $viewHelper6->initializeArgumentsAndRender();

$output3 .= '
			var Aloha,
				alohaBaseUrl = \'';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments7 = array();
$arguments7['path'] = 'Library/aloha';
$arguments7['package'] = 'TYPO3.Neos';
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

$output3 .= $viewHelper9->initializeArgumentsAndRender();

$output3 .= '\';
	</script>

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments10 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments11 = array();
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper13->setArguments($arguments11);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure12);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments10['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper13->initializeArgumentsAndRender());
$arguments10['then'] = NULL;
$arguments10['else'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments16 = array();
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
$output18 = '';

$output18 .= '
			<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments19 = array();
$arguments19['path'] = 'Styles/Includes-built.css';
$arguments19['package'] = 'TYPO3.Neos';
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

$output18 .= '" />
		';
return $output18;
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper22->setArguments($arguments16);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure17);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output15 .= $viewHelper22->initializeArgumentsAndRender();

$output15 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments23 = array();
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
$output25 = '';

$output25 .= '
			<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments26 = array();
$arguments26['path'] = 'Styles/Includes.css';
$arguments26['package'] = 'TYPO3.Neos';
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

$output25 .= $viewHelper28->initializeArgumentsAndRender();

$output25 .= '" />
		';
return $output25;
};
$viewHelper29 = $self->getViewHelper('$viewHelper29', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper29->setArguments($arguments23);
$viewHelper29->setRenderingContext($renderingContext);
$viewHelper29->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output15 .= $viewHelper29->initializeArgumentsAndRender();

$output15 .= '
	';
return $output15;
};
$arguments10['__thenClosure'] = function() use ($renderingContext, $self) {
$output30 = '';

$output30 .= '
			<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments31 = array();
$arguments31['path'] = 'Styles/Includes-built.css';
$arguments31['package'] = 'TYPO3.Neos';
$arguments31['resource'] = NULL;
$arguments31['localize'] = true;
$renderChildrenClosure32 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper33 = $self->getViewHelper('$viewHelper33', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper33->setArguments($arguments31);
$viewHelper33->setRenderingContext($renderingContext);
$viewHelper33->setRenderChildrenClosure($renderChildrenClosure32);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output30 .= $viewHelper33->initializeArgumentsAndRender();

$output30 .= '" />
		';
return $output30;
};
$arguments10['__elseClosure'] = function() use ($renderingContext, $self) {
$output34 = '';

$output34 .= '
			<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments35 = array();
$arguments35['path'] = 'Styles/Includes.css';
$arguments35['package'] = 'TYPO3.Neos';
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

$output34 .= '" />
		';
return $output34;
};
$viewHelper38 = $self->getViewHelper('$viewHelper38', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper38->setArguments($arguments10);
$viewHelper38->setRenderingContext($renderingContext);
$viewHelper38->setRenderChildrenClosure($renderChildrenClosure14);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper38->initializeArgumentsAndRender();

$output3 .= '
';
return $output3;
};
$viewHelper39 = $self->getViewHelper('$viewHelper39', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper');
$viewHelper39->setArguments($arguments1);
$viewHelper39->setRenderingContext($renderingContext);
$viewHelper39->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper

$output0 .= $viewHelper39->initializeArgumentsAndRender();

return $output0;
}


}
#0             9348      