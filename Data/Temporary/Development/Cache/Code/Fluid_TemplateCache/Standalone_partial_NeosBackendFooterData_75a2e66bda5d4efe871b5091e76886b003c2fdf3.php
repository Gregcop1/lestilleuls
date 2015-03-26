<?php class FluidCache_Standalone_partial_NeosBackendFooterData_75a2e66bda5d4efe871b5091e76886b003c2fdf3 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments4 = array();
$arguments4['path'] = 'Library/requirejs/require.js';
$arguments4['package'] = 'TYPO3.Neos';
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

$output3 .= $viewHelper6->initializeArgumentsAndRender();

$output3 .= '"></script>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments7 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments8 = array();
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments7['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper10->initializeArgumentsAndRender());
$arguments7['then'] = NULL;
$arguments7['else'] = NULL;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
$output12 = '';

$output12 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments13 = array();
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments16 = array();
$arguments16['path'] = 'JavaScript/ContentModule-built.js';
$arguments16['package'] = 'TYPO3.Neos';
$arguments16['resource'] = NULL;
$arguments16['localize'] = true;
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper18 = $self->getViewHelper('$viewHelper18', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper18->setArguments($arguments16);
$viewHelper18->setRenderingContext($renderingContext);
$viewHelper18->setRenderChildrenClosure($renderChildrenClosure17);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output15 .= $viewHelper18->initializeArgumentsAndRender();

$output15 .= '"></script>
		';
return $output15;
};
$viewHelper19 = $self->getViewHelper('$viewHelper19', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper19->setArguments($arguments13);
$viewHelper19->setRenderingContext($renderingContext);
$viewHelper19->setRenderChildrenClosure($renderChildrenClosure14);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output12 .= $viewHelper19->initializeArgumentsAndRender();

$output12 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments20 = array();
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
$output22 = '';

$output22 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments23 = array();
$arguments23['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments23['package'] = 'TYPO3.Neos';
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

$output22 .= '"></script>
		';
return $output22;
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper26->setArguments($arguments20);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output12 .= $viewHelper26->initializeArgumentsAndRender();

$output12 .= '
	';
return $output12;
};
$arguments7['__thenClosure'] = function() use ($renderingContext, $self) {
$output27 = '';

$output27 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments28 = array();
$arguments28['path'] = 'JavaScript/ContentModule-built.js';
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

$output27 .= '"></script>
		';
return $output27;
};
$arguments7['__elseClosure'] = function() use ($renderingContext, $self) {
$output31 = '';

$output31 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments32 = array();
$arguments32['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments32['package'] = 'TYPO3.Neos';
$arguments32['resource'] = NULL;
$arguments32['localize'] = true;
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output31 .= $viewHelper34->initializeArgumentsAndRender();

$output31 .= '"></script>
		';
return $output31;
};
$viewHelper35 = $self->getViewHelper('$viewHelper35', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper35->setArguments($arguments7);
$viewHelper35->setRenderingContext($renderingContext);
$viewHelper35->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper35->initializeArgumentsAndRender();

$output3 .= '
';
return $output3;
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper');
$viewHelper36->setArguments($arguments1);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper

$output0 .= $viewHelper36->initializeArgumentsAndRender();

return $output0;
}


}
#0             8522      