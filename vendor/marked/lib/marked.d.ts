// Generated by dts-bundle-generator v9.5.1

export type MarkedToken = (Tokens.Space | Tokens.Code | Tokens.Heading | Tokens.Table | Tokens.Hr | Tokens.Blockquote | Tokens.List | Tokens.ListItem | Tokens.Paragraph | Tokens.HTML | Tokens.Text | Tokens.Def | Tokens.Escape | Tokens.Tag | Tokens.Image | Tokens.Link | Tokens.Strong | Tokens.Em | Tokens.Codespan | Tokens.Br | Tokens.Del);
export type Token = (MarkedToken | Tokens.Generic);
export declare namespace Tokens {
	interface Space {
		type: "space";
		raw: string;
	}
	interface Code {
		type: "code";
		raw: string;
		codeBlockStyle?: "indented" | undefined;
		lang?: string | undefined;
		text: string;
		escaped?: boolean;
	}
	interface Heading {
		type: "heading";
		raw: string;
		depth: number;
		text: string;
		tokens: Token[];
	}
	interface Table {
		type: "table";
		raw: string;
		align: Array<"center" | "left" | "right" | null>;
		header: TableCell[];
		rows: TableCell[][];
	}
	interface TableRow {
		text: string;
	}
	interface TableCell {
		text: string;
		tokens: Token[];
		header: boolean;
		align: "center" | "left" | "right" | null;
	}
	interface Hr {
		type: "hr";
		raw: string;
	}
	interface Blockquote {
		type: "blockquote";
		raw: string;
		text: string;
		tokens: Token[];
	}
	interface List {
		type: "list";
		raw: string;
		ordered: boolean;
		start: number | "";
		loose: boolean;
		items: ListItem[];
	}
	interface ListItem {
		type: "list_item";
		raw: string;
		task: boolean;
		checked?: boolean | undefined;
		loose: boolean;
		text: string;
		tokens: Token[];
	}
	interface Checkbox {
		checked: boolean;
	}
	interface Paragraph {
		type: "paragraph";
		raw: string;
		pre?: boolean | undefined;
		text: string;
		tokens: Token[];
	}
	interface HTML {
		type: "html";
		raw: string;
		pre: boolean;
		text: string;
		block: boolean;
	}
	interface Text {
		type: "text";
		raw: string;
		text: string;
		tokens?: Token[];
	}
	interface Def {
		type: "def";
		raw: string;
		tag: string;
		href: string;
		title: string;
	}
	interface Escape {
		type: "escape";
		raw: string;
		text: string;
	}
	interface Tag {
		type: "text" | "html";
		raw: string;
		inLink: boolean;
		inRawBlock: boolean;
		text: string;
		block: boolean;
	}
	interface Link {
		type: "link";
		raw: string;
		href: string;
		title?: string | null;
		text: string;
		tokens: Token[];
	}
	interface Image {
		type: "image";
		raw: string;
		href: string;
		title: string | null;
		text: string;
	}
	interface Strong {
		type: "strong";
		raw: string;
		text: string;
		tokens: Token[];
	}
	interface Em {
		type: "em";
		raw: string;
		text: string;
		tokens: Token[];
	}
	interface Codespan {
		type: "codespan";
		raw: string;
		text: string;
	}
	interface Br {
		type: "br";
		raw: string;
	}
	interface Del {
		type: "del";
		raw: string;
		text: string;
		tokens: Token[];
	}
	interface Generic {
		[index: string]: any;
		type: string;
		raw: string;
		tokens?: Token[] | undefined;
	}
}
export type Links = Record<string, Pick<Tokens.Link | Tokens.Image, "href" | "title">>;
export type TokensList = Token[] & {
	links: Links;
};
/**
 * Renderer
 */
declare class _Renderer {
	options: MarkedOptions;
	parser: _Parser;
	constructor(options?: MarkedOptions);
	space(token: Tokens.Space): string;
	code({ text, lang, escaped }: Tokens.Code): string;
	blockquote({ tokens }: Tokens.Blockquote): string;
	html({ text }: Tokens.HTML | Tokens.Tag): string;
	heading({ tokens, depth }: Tokens.Heading): string;
	hr(token: Tokens.Hr): string;
	list(token: Tokens.List): string;
	listitem(item: Tokens.ListItem): string;
	checkbox({ checked }: Tokens.Checkbox): string;
	paragraph({ tokens }: Tokens.Paragraph): string;
	table(token: Tokens.Table): string;
	tablerow({ text }: Tokens.TableRow): string;
	tablecell(token: Tokens.TableCell): string;
	/**
	 * span level renderer
	 */
	strong({ tokens }: Tokens.Strong): string;
	em({ tokens }: Tokens.Em): string;
	codespan({ text }: Tokens.Codespan): string;
	br(token: Tokens.Br): string;
	del({ tokens }: Tokens.Del): string;
	link({ href, title, tokens }: Tokens.Link): string;
	image({ href, title, text }: Tokens.Image): string;
	text(token: Tokens.Text | Tokens.Escape | Tokens.Tag): string;
}
/**
 * TextRenderer
 * returns only the textual part of the token
 */
declare class _TextRenderer {
	strong({ text }: Tokens.Strong): string;
	em({ text }: Tokens.Em): string;
	codespan({ text }: Tokens.Codespan): string;
	del({ text }: Tokens.Del): string;
	html({ text }: Tokens.HTML | Tokens.Tag): string;
	text({ text }: Tokens.Text | Tokens.Escape | Tokens.Tag): string;
	link({ text }: Tokens.Link): string;
	image({ text }: Tokens.Image): string;
	br(): string;
}
/**
 * Parsing & Compiling
 */
declare class _Parser {
	options: MarkedOptions;
	renderer: _Renderer;
	textRenderer: _TextRenderer;
	constructor(options?: MarkedOptions);
	/**
	 * Static Parse Method
	 */
	static parse(tokens: Token[], options?: MarkedOptions): string;
	/**
	 * Static Parse Inline Method
	 */
	static parseInline(tokens: Token[], options?: MarkedOptions): string;
	/**
	 * Parse Loop
	 */
	parse(tokens: Token[], top?: boolean): string;
	/**
	 * Parse Inline Tokens
	 */
	parseInline(tokens: Token[], renderer?: _Renderer | _TextRenderer): string;
}
declare const blockNormal: {
	blockquote: RegExp;
	code: RegExp;
	def: RegExp;
	fences: RegExp;
	heading: RegExp;
	hr: RegExp;
	html: RegExp;
	lheading: RegExp;
	list: RegExp;
	newline: RegExp;
	paragraph: RegExp;
	table: RegExp;
	text: RegExp;
};
export type BlockKeys = keyof typeof blockNormal;
declare const inlineNormal: {
	_backpedal: RegExp;
	anyPunctuation: RegExp;
	autolink: RegExp;
	blockSkip: RegExp;
	br: RegExp;
	code: RegExp;
	del: RegExp;
	emStrongLDelim: RegExp;
	emStrongRDelimAst: RegExp;
	emStrongRDelimUnd: RegExp;
	escape: RegExp;
	link: RegExp;
	nolink: RegExp;
	punctuation: RegExp;
	reflink: RegExp;
	reflinkSearch: RegExp;
	tag: RegExp;
	text: RegExp;
	url: RegExp;
};
export type InlineKeys = keyof typeof inlineNormal;
/**
 * exports
 */
export declare const block: {
	normal: {
		blockquote: RegExp;
		code: RegExp;
		def: RegExp;
		fences: RegExp;
		heading: RegExp;
		hr: RegExp;
		html: RegExp;
		lheading: RegExp;
		list: RegExp;
		newline: RegExp;
		paragraph: RegExp;
		table: RegExp;
		text: RegExp;
	};
	gfm: Record<"code" | "blockquote" | "hr" | "html" | "table" | "text" | "heading" | "list" | "paragraph" | "def" | "fences" | "lheading" | "newline", RegExp>;
	pedantic: Record<"code" | "blockquote" | "hr" | "html" | "table" | "text" | "heading" | "list" | "paragraph" | "def" | "fences" | "lheading" | "newline", RegExp>;
};
export declare const inline: {
	normal: {
		_backpedal: RegExp;
		anyPunctuation: RegExp;
		autolink: RegExp;
		blockSkip: RegExp;
		br: RegExp;
		code: RegExp;
		del: RegExp;
		emStrongLDelim: RegExp;
		emStrongRDelimAst: RegExp;
		emStrongRDelimUnd: RegExp;
		escape: RegExp;
		link: RegExp;
		nolink: RegExp;
		punctuation: RegExp;
		reflink: RegExp;
		reflinkSearch: RegExp;
		tag: RegExp;
		text: RegExp;
		url: RegExp;
	};
	gfm: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
	breaks: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
	pedantic: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
};
export interface Rules {
	block: Record<BlockKeys, RegExp>;
	inline: Record<InlineKeys, RegExp>;
}
/**
 * Tokenizer
 */
declare class _Tokenizer {
	options: MarkedOptions;
	rules: Rules;
	lexer: _Lexer;
	constructor(options?: MarkedOptions);
	space(src: string): Tokens.Space | undefined;
	code(src: string): Tokens.Code | undefined;
	fences(src: string): Tokens.Code | undefined;
	heading(src: string): Tokens.Heading | undefined;
	hr(src: string): Tokens.Hr | undefined;
	blockquote(src: string): Tokens.Blockquote | undefined;
	list(src: string): Tokens.List | undefined;
	html(src: string): Tokens.HTML | undefined;
	def(src: string): Tokens.Def | undefined;
	table(src: string): Tokens.Table | undefined;
	lheading(src: string): Tokens.Heading | undefined;
	paragraph(src: string): Tokens.Paragraph | undefined;
	text(src: string): Tokens.Text | undefined;
	escape(src: string): Tokens.Escape | undefined;
	tag(src: string): Tokens.Tag | undefined;
	link(src: string): Tokens.Link | Tokens.Image | undefined;
	reflink(src: string, links: Links): Tokens.Link | Tokens.Image | Tokens.Text | undefined;
	emStrong(src: string, maskedSrc: string, prevChar?: string): Tokens.Em | Tokens.Strong | undefined;
	codespan(src: string): Tokens.Codespan | undefined;
	br(src: string): Tokens.Br | undefined;
	del(src: string): Tokens.Del | undefined;
	autolink(src: string): Tokens.Link | undefined;
	url(src: string): Tokens.Link | undefined;
	inlineText(src: string): Tokens.Text | undefined;
}
declare class _Hooks {
	options: MarkedOptions;
	constructor(options?: MarkedOptions);
	static passThroughHooks: Set<string>;
	/**
	 * Process markdown before marked
	 */
	preprocess(markdown: string): string;
	/**
	 * Process HTML after marked is finished
	 */
	postprocess(html: string): string;
	/**
	 * Process all tokens before walk tokens
	 */
	processAllTokens(tokens: Token[] | TokensList): Token[] | TokensList;
}
export interface TokenizerThis {
	lexer: _Lexer;
}
export type TokenizerExtensionFunction = (this: TokenizerThis, src: string, tokens: Token[] | TokensList) => Tokens.Generic | undefined;
export type TokenizerStartFunction = (this: TokenizerThis, src: string) => number | void;
export interface TokenizerExtension {
	name: string;
	level: "block" | "inline";
	start?: TokenizerStartFunction | undefined;
	tokenizer: TokenizerExtensionFunction;
	childTokens?: string[] | undefined;
}
export interface RendererThis {
	parser: _Parser;
}
export type RendererExtensionFunction = (this: RendererThis, token: Tokens.Generic) => string | false | undefined;
export interface RendererExtension {
	name: string;
	renderer: RendererExtensionFunction;
}
export type TokenizerAndRendererExtension = TokenizerExtension | RendererExtension | (TokenizerExtension & RendererExtension);
export type HooksApi = Omit<_Hooks, "constructor" | "options">;
export type HooksObject = {
	[K in keyof HooksApi]?: (this: _Hooks, ...args: Parameters<HooksApi[K]>) => ReturnType<HooksApi[K]> | Promise<ReturnType<HooksApi[K]>>;
};
export type RendererApi = Omit<_Renderer, "constructor" | "options" | "parser">;
export type RendererObject = {
	[K in keyof RendererApi]?: (this: _Renderer, ...args: Parameters<RendererApi[K]>) => ReturnType<RendererApi[K]> | false;
};
export type TokenizerApi = Omit<_Tokenizer, "constructor" | "options" | "rules" | "lexer">;
export type TokenizerObject = {
	[K in keyof TokenizerApi]?: (this: _Tokenizer, ...args: Parameters<TokenizerApi[K]>) => ReturnType<TokenizerApi[K]> | false;
};
export interface MarkedExtension {
	/**
	 * True will tell marked to await any walkTokens functions before parsing the tokens and returning an HTML string.
	 */
	async?: boolean;
	/**
	 * Enable GFM line breaks. This option requires the gfm option to be true.
	 */
	breaks?: boolean | undefined;
	/**
	 * Add tokenizers and renderers to marked
	 */
	extensions?: TokenizerAndRendererExtension[] | undefined | null;
	/**
	 * Enable GitHub flavored markdown.
	 */
	gfm?: boolean | undefined;
	/**
	 * Hooks are methods that hook into some part of marked.
	 * preprocess is called to process markdown before sending it to marked.
	 * processAllTokens is called with the TokensList before walkTokens.
	 * postprocess is called to process html after marked has finished parsing.
	 */
	hooks?: HooksObject | undefined | null;
	/**
	 * Conform to obscure parts of markdown.pl as much as possible. Don't fix any of the original markdown bugs or poor behavior.
	 */
	pedantic?: boolean | undefined;
	/**
	 * Type: object Default: new Renderer()
	 *
	 * An object containing functions to render tokens to HTML.
	 */
	renderer?: RendererObject | undefined | null;
	/**
	 * Shows an HTML error message when rendering fails.
	 */
	silent?: boolean | undefined;
	/**
	 * The tokenizer defines how to turn markdown text into tokens.
	 */
	tokenizer?: TokenizerObject | undefined | null;
	/**
	 * The walkTokens function gets called with every token.
	 * Child tokens are called before moving on to sibling tokens.
	 * Each token is passed by reference so updates are persisted when passed to the parser.
	 * The return value of the function is ignored.
	 */
	walkTokens?: ((token: Token) => void | Promise<void>) | undefined | null;
	/**
	 * Use the new renderer that accepts an object instead of individual parameters.
	 * This option will be removed and default to true in the next major version.
	 */
	useNewRenderer?: boolean | undefined;
}
export interface MarkedOptions extends Omit<MarkedExtension, "useNewRenderer" | "hooks" | "renderer" | "tokenizer" | "extensions" | "walkTokens"> {
	/**
	 * Hooks are methods that hook into some part of marked.
	 */
	hooks?: _Hooks | undefined | null;
	/**
	 * Type: object Default: new Renderer()
	 *
	 * An object containing functions to render tokens to HTML.
	 */
	renderer?: _Renderer | undefined | null;
	/**
	 * The tokenizer defines how to turn markdown text into tokens.
	 */
	tokenizer?: _Tokenizer | undefined | null;
	/**
	 * Custom extensions
	 */
	extensions?: null | {
		renderers: {
			[name: string]: RendererExtensionFunction;
		};
		childTokens: {
			[name: string]: string[];
		};
		inline?: TokenizerExtensionFunction[];
		block?: TokenizerExtensionFunction[];
		startInline?: TokenizerStartFunction[];
		startBlock?: TokenizerStartFunction[];
	};
	/**
	 * walkTokens function returns array of values for Promise.all
	 */
	walkTokens?: null | ((token: Token) => void | Promise<void> | (void | Promise<void>)[]);
}
/**
 * Block Lexer
 */
declare class _Lexer {
	tokens: TokensList;
	options: MarkedOptions;
	state: {
		inLink: boolean;
		inRawBlock: boolean;
		top: boolean;
	};
	private tokenizer;
	private inlineQueue;
	constructor(options?: MarkedOptions);
	/**
	 * Expose Rules
	 */
	static get rules(): {
		block: {
			normal: {
				blockquote: RegExp;
				code: RegExp;
				def: RegExp;
				fences: RegExp;
				heading: RegExp;
				hr: RegExp;
				html: RegExp;
				lheading: RegExp;
				list: RegExp;
				newline: RegExp;
				paragraph: RegExp;
				table: RegExp;
				text: RegExp;
			};
			gfm: Record<"code" | "blockquote" | "hr" | "html" | "table" | "text" | "heading" | "list" | "paragraph" | "def" | "fences" | "lheading" | "newline", RegExp>;
			pedantic: Record<"code" | "blockquote" | "hr" | "html" | "table" | "text" | "heading" | "list" | "paragraph" | "def" | "fences" | "lheading" | "newline", RegExp>;
		};
		inline: {
			normal: {
				_backpedal: RegExp;
				anyPunctuation: RegExp;
				autolink: RegExp;
				blockSkip: RegExp;
				br: RegExp;
				code: RegExp;
				del: RegExp;
				emStrongLDelim: RegExp;
				emStrongRDelimAst: RegExp;
				emStrongRDelimUnd: RegExp;
				escape: RegExp;
				link: RegExp;
				nolink: RegExp;
				punctuation: RegExp;
				reflink: RegExp;
				reflinkSearch: RegExp;
				tag: RegExp;
				text: RegExp;
				url: RegExp;
			};
			gfm: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
			breaks: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
			pedantic: Record<"link" | "code" | "url" | "br" | "del" | "text" | "escape" | "tag" | "reflink" | "autolink" | "nolink" | "_backpedal" | "anyPunctuation" | "blockSkip" | "emStrongLDelim" | "emStrongRDelimAst" | "emStrongRDelimUnd" | "punctuation" | "reflinkSearch", RegExp>;
		};
	};
	/**
	 * Static Lex Method
	 */
	static lex(src: string, options?: MarkedOptions): TokensList;
	/**
	 * Static Lex Inline Method
	 */
	static lexInline(src: string, options?: MarkedOptions): Token[];
	/**
	 * Preprocessing
	 */
	lex(src: string): TokensList;
	/**
	 * Lexing
	 */
	blockTokens(src: string, tokens?: Token[], lastParagraphClipped?: boolean): Token[];
	blockTokens(src: string, tokens?: TokensList, lastParagraphClipped?: boolean): TokensList;
	inline(src: string, tokens?: Token[]): Token[];
	/**
	 * Lexing/Compiling
	 */
	inlineTokens(src: string, tokens?: Token[]): Token[];
}
/**
 * Gets the original marked default options.
 */
declare function _getDefaults(): MarkedOptions;
declare let _defaults: MarkedOptions;
export type MaybePromise = void | Promise<void>;
export declare class Marked {
	#private;
	defaults: MarkedOptions;
	options: (opt: MarkedOptions) => this;
	parse: (src: string, options?: MarkedOptions | undefined | null) => string | Promise<string>;
	parseInline: (src: string, options?: MarkedOptions | undefined | null) => string | Promise<string>;
	Parser: typeof _Parser;
	Renderer: typeof _Renderer;
	TextRenderer: typeof _TextRenderer;
	Lexer: typeof _Lexer;
	Tokenizer: typeof _Tokenizer;
	Hooks: typeof _Hooks;
	constructor(...args: MarkedExtension[]);
	/**
	 * Run callback for every token
	 */
	walkTokens(tokens: Token[] | TokensList, callback: (token: Token) => MaybePromise | MaybePromise[]): MaybePromise[];
	use(...args: MarkedExtension[]): this;
	setOptions(opt: MarkedOptions): this;
	lexer(src: string, options?: MarkedOptions): TokensList;
	parser(tokens: Token[], options?: MarkedOptions): string;
}
/**
 * Compiles markdown to HTML asynchronously.
 *
 * @param src String of markdown source to be compiled
 * @param options Hash of options, having async: true
 * @return Promise of string of compiled HTML
 */
export declare function marked(src: string, options: MarkedOptions & {
	async: true;
}): Promise<string>;
/**
 * Compiles markdown to HTML.
 *
 * @param src String of markdown source to be compiled
 * @param options Optional hash of options
 * @return String of compiled HTML. Will be a Promise of string if async is set to true by any extensions.
 */
export declare function marked(src: string, options?: MarkedOptions): string | Promise<string>;
export declare namespace marked {
	var options: (options: MarkedOptions) => typeof marked;
	var setOptions: (options: MarkedOptions) => typeof marked;
	var getDefaults: typeof _getDefaults;
	var defaults: MarkedOptions;
	var use: (...args: MarkedExtension[]) => typeof marked;
	var walkTokens: (tokens: Token[] | TokensList, callback: (token: Token) => MaybePromise | MaybePromise[]) => MaybePromise[];
	var parseInline: (src: string, options?: MarkedOptions | null | undefined) => string | Promise<string>;
	var Parser: typeof _Parser;
	var parser: typeof _Parser.parse;
	var Renderer: typeof _Renderer;
	var TextRenderer: typeof _TextRenderer;
	var Lexer: typeof _Lexer;
	var lexer: typeof _Lexer.lex;
	var Tokenizer: typeof _Tokenizer;
	var Hooks: typeof _Hooks;
	var parse: typeof marked;
}
export declare const options: (options: MarkedOptions) => typeof marked;
export declare const setOptions: (options: MarkedOptions) => typeof marked;
export declare const use: (...args: MarkedExtension[]) => typeof marked;
export declare const walkTokens: (tokens: Token[] | TokensList, callback: (token: Token) => MaybePromise | MaybePromise[]) => MaybePromise[];
export declare const parseInline: (src: string, options?: MarkedOptions | null | undefined) => string | Promise<string>;
export declare const parse: typeof marked;
export declare const parser: typeof _Parser.parse;
export declare const lexer: typeof _Lexer.lex;

export {
	_Hooks as Hooks,
	_Lexer as Lexer,
	_Parser as Parser,
	_Renderer as Renderer,
	_TextRenderer as TextRenderer,
	_Tokenizer as Tokenizer,
	_defaults as defaults,
	_getDefaults as getDefaults,
};

export {};
