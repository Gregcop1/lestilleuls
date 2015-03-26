<?php class FluidCache_Standalone_template_file_Menu_d644152e4ea0af97e6903b57ab8a9342d44792ae extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
 * section itemsList
 */
public function section_972aa32a62a3edb2361f354fbe5a593300986184(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments1 = array();
$arguments1['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'items', $renderingContext);
$arguments1['as'] = 'item';
$arguments1['key'] = '';
$arguments1['reverse'] = false;
$arguments1['iteration'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
		<li';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments4 = array();
$arguments4['value'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\TypoScript\ViewHelpers\RenderViewHelper
$arguments6 = array();
$output7 = '';

$output7 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.state', $renderingContext);

$output7 .= '.attributes';
$arguments6['path'] = $output7;
// Rendering Array
$array8 = array();
$array8['item'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item', $renderingContext);
$arguments6['context'] = $array8;
$arguments6['typoScriptPackageKey'] = NULL;
$arguments6['typoScriptFilePathPattern'] = NULL;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\TypoScript\ViewHelpers\RenderViewHelper');
$viewHelper10->setArguments($arguments6);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\TypoScript\ViewHelpers\RenderViewHelper
return $viewHelper10->initializeArgumentsAndRender();
};
$viewHelper11 = $self->getViewHelper('$viewHelper11', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper11->setArguments($arguments4);
$viewHelper11->setRenderingContext($renderingContext);
$viewHelper11->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output3 .= $viewHelper11->initializeArgumentsAndRender();

$output3 .= '>
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper
$arguments12 = array();
$arguments12['node'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.node', $renderingContext);
$arguments12['additionalAttributes'] = NULL;
$arguments12['data'] = NULL;
$arguments12['format'] = NULL;
$arguments12['absolute'] = false;
$arguments12['arguments'] = array (
);
$arguments12['section'] = '';
$arguments12['addQueryString'] = false;
$arguments12['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments12['baseNodeName'] = 'documentNode';
$arguments12['nodeVariableName'] = 'linkedNode';
$arguments12['resolveShortcuts'] = true;
$arguments12['class'] = NULL;
$arguments12['dir'] = NULL;
$arguments12['id'] = NULL;
$arguments12['lang'] = NULL;
$arguments12['style'] = NULL;
$arguments12['title'] = NULL;
$arguments12['accesskey'] = NULL;
$arguments12['tabindex'] = NULL;
$arguments12['onclick'] = NULL;
$arguments12['name'] = NULL;
$arguments12['rel'] = NULL;
$arguments12['rev'] = NULL;
$arguments12['target'] = NULL;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper14 = $self->getViewHelper('$viewHelper14', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\NodeViewHelper');
$viewHelper14->setArguments($arguments12);
$viewHelper14->setRenderingContext($renderingContext);
$viewHelper14->setRenderChildrenClosure($renderChildrenClosure13);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper

$output3 .= $viewHelper14->initializeArgumentsAndRender();

$output3 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments15 = array();
// Rendering Boolean node
$arguments15['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.subItems', $renderingContext));
$arguments15['then'] = NULL;
$arguments15['else'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
$output17 = '';

$output17 .= '
			<ul>
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments18 = array();
$arguments18['section'] = 'itemsList';
// Rendering Array
$array19 = array();
$array19['items'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.subItems', $renderingContext);
$arguments18['arguments'] = $array19;
$arguments18['partial'] = NULL;
$arguments18['optional'] = false;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper21 = $self->getViewHelper('$viewHelper21', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper21->setArguments($arguments18);
$viewHelper21->setRenderingContext($renderingContext);
$viewHelper21->setRenderChildrenClosure($renderChildrenClosure20);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output17 .= $viewHelper21->initializeArgumentsAndRender();

$output17 .= '
			</ul>
		';
return $output17;
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper22->setArguments($arguments15);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper22->initializeArgumentsAndRender();

$output3 .= '
		</li>
	';
return $output3;
};

$output0 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments1, $renderChildrenClosure2, $renderingContext);

$output0 .= '
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output23 = '';

$output23 .= '

<ul';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments24 = array();
$arguments24['value'] = NULL;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'attributes', $renderingContext);
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper26->setArguments($arguments24);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output23 .= $viewHelper26->initializeArgumentsAndRender();

$output23 .= '>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments27 = array();
$arguments27['section'] = 'itemsList';
// Rendering Array
$array28 = array();
$array28['items'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'items', $renderingContext);
$arguments27['arguments'] = $array28;
$arguments27['partial'] = NULL;
$arguments27['optional'] = false;
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper30->setArguments($arguments27);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure29);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output23 .= $viewHelper30->initializeArgumentsAndRender();

$output23 .= '
</ul>

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments31 = array();
$arguments31['name'] = 'itemsList';
$renderChildrenClosure32 = function() use ($renderingContext, $self) {
$output33 = '';

$output33 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments34 = array();
$arguments34['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'items', $renderingContext);
$arguments34['as'] = 'item';
$arguments34['key'] = '';
$arguments34['reverse'] = false;
$arguments34['iteration'] = NULL;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
$output36 = '';

$output36 .= '
		<li';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments37 = array();
$arguments37['value'] = NULL;
$renderChildrenClosure38 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\TypoScript\ViewHelpers\RenderViewHelper
$arguments39 = array();
$output40 = '';

$output40 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.state', $renderingContext);

$output40 .= '.attributes';
$arguments39['path'] = $output40;
// Rendering Array
$array41 = array();
$array41['item'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item', $renderingContext);
$arguments39['context'] = $array41;
$arguments39['typoScriptPackageKey'] = NULL;
$arguments39['typoScriptFilePathPattern'] = NULL;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper43 = $self->getViewHelper('$viewHelper43', $renderingContext, 'TYPO3\TypoScript\ViewHelpers\RenderViewHelper');
$viewHelper43->setArguments($arguments39);
$viewHelper43->setRenderingContext($renderingContext);
$viewHelper43->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\TypoScript\ViewHelpers\RenderViewHelper
return $viewHelper43->initializeArgumentsAndRender();
};
$viewHelper44 = $self->getViewHelper('$viewHelper44', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper44->setArguments($arguments37);
$viewHelper44->setRenderingContext($renderingContext);
$viewHelper44->setRenderChildrenClosure($renderChildrenClosure38);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output36 .= $viewHelper44->initializeArgumentsAndRender();

$output36 .= '>
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper
$arguments45 = array();
$arguments45['node'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.node', $renderingContext);
$arguments45['additionalAttributes'] = NULL;
$arguments45['data'] = NULL;
$arguments45['format'] = NULL;
$arguments45['absolute'] = false;
$arguments45['arguments'] = array (
);
$arguments45['section'] = '';
$arguments45['addQueryString'] = false;
$arguments45['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments45['baseNodeName'] = 'documentNode';
$arguments45['nodeVariableName'] = 'linkedNode';
$arguments45['resolveShortcuts'] = true;
$arguments45['class'] = NULL;
$arguments45['dir'] = NULL;
$arguments45['id'] = NULL;
$arguments45['lang'] = NULL;
$arguments45['style'] = NULL;
$arguments45['title'] = NULL;
$arguments45['accesskey'] = NULL;
$arguments45['tabindex'] = NULL;
$arguments45['onclick'] = NULL;
$arguments45['name'] = NULL;
$arguments45['rel'] = NULL;
$arguments45['rev'] = NULL;
$arguments45['target'] = NULL;
$renderChildrenClosure46 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper47 = $self->getViewHelper('$viewHelper47', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\NodeViewHelper');
$viewHelper47->setArguments($arguments45);
$viewHelper47->setRenderingContext($renderingContext);
$viewHelper47->setRenderChildrenClosure($renderChildrenClosure46);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper

$output36 .= $viewHelper47->initializeArgumentsAndRender();

$output36 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments48 = array();
// Rendering Boolean node
$arguments48['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.subItems', $renderingContext));
$arguments48['then'] = NULL;
$arguments48['else'] = NULL;
$renderChildrenClosure49 = function() use ($renderingContext, $self) {
$output50 = '';

$output50 .= '
			<ul>
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments51 = array();
$arguments51['section'] = 'itemsList';
// Rendering Array
$array52 = array();
$array52['items'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.subItems', $renderingContext);
$arguments51['arguments'] = $array52;
$arguments51['partial'] = NULL;
$arguments51['optional'] = false;
$renderChildrenClosure53 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper54 = $self->getViewHelper('$viewHelper54', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper54->setArguments($arguments51);
$viewHelper54->setRenderingContext($renderingContext);
$viewHelper54->setRenderChildrenClosure($renderChildrenClosure53);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output50 .= $viewHelper54->initializeArgumentsAndRender();

$output50 .= '
			</ul>
		';
return $output50;
};
$viewHelper55 = $self->getViewHelper('$viewHelper55', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper55->setArguments($arguments48);
$viewHelper55->setRenderingContext($renderingContext);
$viewHelper55->setRenderChildrenClosure($renderChildrenClosure49);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output36 .= $viewHelper55->initializeArgumentsAndRender();

$output36 .= '
		</li>
	';
return $output36;
};

$output33 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments34, $renderChildrenClosure35, $renderingContext);

$output33 .= '
';
return $output33;
};

$output23 .= '';

$output23 .= '
';

return $output23;
}


}
#0             15109     