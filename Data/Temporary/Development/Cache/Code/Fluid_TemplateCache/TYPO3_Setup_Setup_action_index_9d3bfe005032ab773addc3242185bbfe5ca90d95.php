<?php class FluidCache_TYPO3_Setup_Setup_action_index_9d3bfe005032ab773addc3242185bbfe5ca90d95 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments1 = array();
$arguments1['controller'] = 'Login';
$arguments1['action'] = 'logout';
$arguments1['class'] = 'btn pull-right logout btn-mini';
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['arguments'] = array (
);
$arguments1['package'] = NULL;
$arguments1['subpackage'] = NULL;
$arguments1['section'] = '';
$arguments1['format'] = '';
$arguments1['additionalParams'] = array (
);
$arguments1['addQueryString'] = false;
$arguments1['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments1['useParentRequest'] = false;
$arguments1['absolute'] = true;
$arguments1['dir'] = NULL;
$arguments1['id'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$arguments1['name'] = NULL;
$arguments1['rel'] = NULL;
$arguments1['rev'] = NULL;
$arguments1['target'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return '
		<span class="glyphicon glyphicon-off"></span>
		Logout
	';
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments4 = array();
$arguments4['value'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form', $renderingContext);
};
$viewHelper6 = $self->getViewHelper('$viewHelper6', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper6->setArguments($arguments4);
$viewHelper6->setRenderingContext($renderingContext);
$viewHelper6->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output0 .= $viewHelper6->initializeArgumentsAndRender();

$output0 .= '
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output7 = '';

$output7 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments8 = array();
$arguments8['name'] = 'Default';
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output7 .= $viewHelper10->initializeArgumentsAndRender();

$output7 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments11 = array();
$arguments11['name'] = 'content';
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
$output13 = '';

$output13 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments14 = array();
$arguments14['controller'] = 'Login';
$arguments14['action'] = 'logout';
$arguments14['class'] = 'btn pull-right logout btn-mini';
$arguments14['additionalAttributes'] = NULL;
$arguments14['data'] = NULL;
$arguments14['arguments'] = array (
);
$arguments14['package'] = NULL;
$arguments14['subpackage'] = NULL;
$arguments14['section'] = '';
$arguments14['format'] = '';
$arguments14['additionalParams'] = array (
);
$arguments14['addQueryString'] = false;
$arguments14['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments14['useParentRequest'] = false;
$arguments14['absolute'] = true;
$arguments14['dir'] = NULL;
$arguments14['id'] = NULL;
$arguments14['lang'] = NULL;
$arguments14['style'] = NULL;
$arguments14['title'] = NULL;
$arguments14['accesskey'] = NULL;
$arguments14['tabindex'] = NULL;
$arguments14['onclick'] = NULL;
$arguments14['name'] = NULL;
$arguments14['rel'] = NULL;
$arguments14['rev'] = NULL;
$arguments14['target'] = NULL;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return '
		<span class="glyphicon glyphicon-off"></span>
		Logout
	';
};
$viewHelper16 = $self->getViewHelper('$viewHelper16', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper16->setArguments($arguments14);
$viewHelper16->setRenderingContext($renderingContext);
$viewHelper16->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output13 .= $viewHelper16->initializeArgumentsAndRender();

$output13 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments17 = array();
$arguments17['value'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'form', $renderingContext);
};
$viewHelper19 = $self->getViewHelper('$viewHelper19', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper19->setArguments($arguments17);
$viewHelper19->setRenderingContext($renderingContext);
$viewHelper19->setRenderChildrenClosure($renderChildrenClosure18);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output13 .= $viewHelper19->initializeArgumentsAndRender();

$output13 .= '
';
return $output13;
};

$output7 .= '';

return $output7;
}


}
#0             6636      