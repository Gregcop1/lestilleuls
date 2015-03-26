<?php class FluidCache_Standalone_template_file_NeosBackendMessage_0627d68c23eddf1e3a423f858aa756c565e74243 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments4 = array();
// Rendering Boolean node
$arguments4['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments4['then'] = NULL;
$arguments4['else'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments7 = array();
$arguments7['partial'] = 'NeosBackendHeaderData';
$arguments7['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments7['section'] = NULL;
$arguments7['optional'] = false;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper9 = $self->getViewHelper('$viewHelper9', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper9->setArguments($arguments7);
$viewHelper9->setRenderingContext($renderingContext);
$viewHelper9->setRenderChildrenClosure($renderChildrenClosure8);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output6 .= $viewHelper9->initializeArgumentsAndRender();

$output6 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments10 = array();
$arguments10['partial'] = 'NeosBackendEndpoints';
$arguments10['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments10['section'] = NULL;
$arguments10['optional'] = false;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper12 = $self->getViewHelper('$viewHelper12', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper12->setArguments($arguments10);
$viewHelper12->setRenderingContext($renderingContext);
$viewHelper12->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output6 .= $viewHelper12->initializeArgumentsAndRender();

$output6 .= '
	';
return $output6;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper13->setArguments($arguments4);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper13->initializeArgumentsAndRender();

$output0 .= '
';

return $output0;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output14 = '';

$output14 .= '
	<body class="neos">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments15 = array();
// Rendering Boolean node
$arguments15['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments15['then'] = NULL;
$arguments15['else'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments17 = array();
$arguments17['value'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'metaData', $renderingContext);
};
$viewHelper19 = $self->getViewHelper('$viewHelper19', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper19->setArguments($arguments17);
$viewHelper19->setRenderingContext($renderingContext);
$viewHelper19->setRenderChildrenClosure($renderChildrenClosure18);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
return $viewHelper19->initializeArgumentsAndRender();
};
$viewHelper20 = $self->getViewHelper('$viewHelper20', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper20->setArguments($arguments15);
$viewHelper20->setRenderingContext($renderingContext);
$viewHelper20->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output14 .= $viewHelper20->initializeArgumentsAndRender();

$output14 .= '
		<div class="neos-error-screen">
			<div class="neos-message-icon">
				<i class="icon-warning-sign"></i>
			</div>
			<h1>An Error Occurred</h1>
			<p>Sorry, the page could not be rendered.</p>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments21 = array();
$arguments21['value'] = NULL;
$renderChildrenClosure22 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'message', $renderingContext);
};
$viewHelper23 = $self->getViewHelper('$viewHelper23', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper23->setArguments($arguments21);
$viewHelper23->setRenderingContext($renderingContext);
$viewHelper23->setRenderChildrenClosure($renderChildrenClosure22);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output14 .= $viewHelper23->initializeArgumentsAndRender();

$output14 .= '
		</div>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments24 = array();
// Rendering Boolean node
$arguments24['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments24['then'] = NULL;
$arguments24['else'] = NULL;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
$output26 = '';

$output26 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments27 = array();
$arguments27['partial'] = 'NeosBackendContainer';
$arguments27['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments27['section'] = NULL;
$arguments27['optional'] = false;
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper29 = $self->getViewHelper('$viewHelper29', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper29->setArguments($arguments27);
$viewHelper29->setRenderingContext($renderingContext);
$viewHelper29->setRenderChildrenClosure($renderChildrenClosure28);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output26 .= $viewHelper29->initializeArgumentsAndRender();

$output26 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments30 = array();
$arguments30['partial'] = 'NeosBackendFooterData';
$arguments30['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments30['section'] = NULL;
$arguments30['optional'] = false;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper32 = $self->getViewHelper('$viewHelper32', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper32->setArguments($arguments30);
$viewHelper32->setRenderingContext($renderingContext);
$viewHelper32->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output26 .= $viewHelper32->initializeArgumentsAndRender();

$output26 .= '
		';
return $output26;
};
$viewHelper33 = $self->getViewHelper('$viewHelper33', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper33->setArguments($arguments24);
$viewHelper33->setRenderingContext($renderingContext);
$viewHelper33->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output14 .= $viewHelper33->initializeArgumentsAndRender();

$output14 .= '
	</body>
';

return $output14;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output34 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments35 = array();
$arguments35['name'] = 'Default';
$renderChildrenClosure36 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper37 = $self->getViewHelper('$viewHelper37', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper37->setArguments($arguments35);
$viewHelper37->setRenderingContext($renderingContext);
$viewHelper37->setRenderChildrenClosure($renderChildrenClosure36);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output34 .= $viewHelper37->initializeArgumentsAndRender();

$output34 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments38 = array();
$arguments38['name'] = 'head';
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
$output40 = '';

$output40 .= '
	<title>TYPO3 Neos Error</title>
	<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments41 = array();
$arguments41['path'] = 'Styles/Error.css';
$arguments41['package'] = 'TYPO3.Neos';
$arguments41['resource'] = NULL;
$arguments41['localize'] = true;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper43 = $self->getViewHelper('$viewHelper43', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper43->setArguments($arguments41);
$viewHelper43->setRenderingContext($renderingContext);
$viewHelper43->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output40 .= $viewHelper43->initializeArgumentsAndRender();

$output40 .= '" />
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments44 = array();
// Rendering Boolean node
$arguments44['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments44['then'] = NULL;
$arguments44['else'] = NULL;
$renderChildrenClosure45 = function() use ($renderingContext, $self) {
$output46 = '';

$output46 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments47 = array();
$arguments47['partial'] = 'NeosBackendHeaderData';
$arguments47['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments47['section'] = NULL;
$arguments47['optional'] = false;
$renderChildrenClosure48 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper49 = $self->getViewHelper('$viewHelper49', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper49->setArguments($arguments47);
$viewHelper49->setRenderingContext($renderingContext);
$viewHelper49->setRenderChildrenClosure($renderChildrenClosure48);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output46 .= $viewHelper49->initializeArgumentsAndRender();

$output46 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments50 = array();
$arguments50['partial'] = 'NeosBackendEndpoints';
$arguments50['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments50['section'] = NULL;
$arguments50['optional'] = false;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper52 = $self->getViewHelper('$viewHelper52', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper52->setArguments($arguments50);
$viewHelper52->setRenderingContext($renderingContext);
$viewHelper52->setRenderChildrenClosure($renderChildrenClosure51);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output46 .= $viewHelper52->initializeArgumentsAndRender();

$output46 .= '
	';
return $output46;
};
$viewHelper53 = $self->getViewHelper('$viewHelper53', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper53->setArguments($arguments44);
$viewHelper53->setRenderingContext($renderingContext);
$viewHelper53->setRenderChildrenClosure($renderChildrenClosure45);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output40 .= $viewHelper53->initializeArgumentsAndRender();

$output40 .= '
';
return $output40;
};

$output34 .= '';

$output34 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments54 = array();
$arguments54['name'] = 'body';
$renderChildrenClosure55 = function() use ($renderingContext, $self) {
$output56 = '';

$output56 .= '
	<body class="neos">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments57 = array();
// Rendering Boolean node
$arguments57['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments57['then'] = NULL;
$arguments57['else'] = NULL;
$renderChildrenClosure58 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments59 = array();
$arguments59['value'] = NULL;
$renderChildrenClosure60 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'metaData', $renderingContext);
};
$viewHelper61 = $self->getViewHelper('$viewHelper61', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper61->setArguments($arguments59);
$viewHelper61->setRenderingContext($renderingContext);
$viewHelper61->setRenderChildrenClosure($renderChildrenClosure60);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
return $viewHelper61->initializeArgumentsAndRender();
};
$viewHelper62 = $self->getViewHelper('$viewHelper62', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper62->setArguments($arguments57);
$viewHelper62->setRenderingContext($renderingContext);
$viewHelper62->setRenderChildrenClosure($renderChildrenClosure58);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output56 .= $viewHelper62->initializeArgumentsAndRender();

$output56 .= '
		<div class="neos-error-screen">
			<div class="neos-message-icon">
				<i class="icon-warning-sign"></i>
			</div>
			<h1>An Error Occurred</h1>
			<p>Sorry, the page could not be rendered.</p>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments63 = array();
$arguments63['value'] = NULL;
$renderChildrenClosure64 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'message', $renderingContext);
};
$viewHelper65 = $self->getViewHelper('$viewHelper65', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper65->setArguments($arguments63);
$viewHelper65->setRenderingContext($renderingContext);
$viewHelper65->setRenderChildrenClosure($renderChildrenClosure64);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output56 .= $viewHelper65->initializeArgumentsAndRender();

$output56 .= '
		</div>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments66 = array();
// Rendering Boolean node
$arguments66['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isBackend', $renderingContext));
$arguments66['then'] = NULL;
$arguments66['else'] = NULL;
$renderChildrenClosure67 = function() use ($renderingContext, $self) {
$output68 = '';

$output68 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments69 = array();
$arguments69['partial'] = 'NeosBackendContainer';
$arguments69['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments69['section'] = NULL;
$arguments69['optional'] = false;
$renderChildrenClosure70 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper71 = $self->getViewHelper('$viewHelper71', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper71->setArguments($arguments69);
$viewHelper71->setRenderingContext($renderingContext);
$viewHelper71->setRenderChildrenClosure($renderChildrenClosure70);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output68 .= $viewHelper71->initializeArgumentsAndRender();

$output68 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments72 = array();
$arguments72['partial'] = 'NeosBackendFooterData';
$arguments72['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments72['section'] = NULL;
$arguments72['optional'] = false;
$renderChildrenClosure73 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper74 = $self->getViewHelper('$viewHelper74', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper74->setArguments($arguments72);
$viewHelper74->setRenderingContext($renderingContext);
$viewHelper74->setRenderChildrenClosure($renderChildrenClosure73);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output68 .= $viewHelper74->initializeArgumentsAndRender();

$output68 .= '
		';
return $output68;
};
$viewHelper75 = $self->getViewHelper('$viewHelper75', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper75->setArguments($arguments66);
$viewHelper75->setRenderingContext($renderingContext);
$viewHelper75->setRenderChildrenClosure($renderChildrenClosure67);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output56 .= $viewHelper75->initializeArgumentsAndRender();

$output56 .= '
	</body>
';
return $output56;
};

$output34 .= '';

return $output34;
}


}
#0             20513     