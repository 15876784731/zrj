# zetank-swiper

>轮播图组件，包含多种样式

###安装
下载组件到项目即可，本组件符合[easycom](https://uniapp.dcloud.io/collocation/pages?id=easycom)规范

###使用
```html
<view>
	<zetank-swiper :height="200" :radius="20" :imageList="imageList" type="default" @clickImg="clickImg"></zetank-swiper>
	<zetank-swiper :height="200" :radius="20" :imageList="imageList" type="default-nav" @clickImg="clickImg"></zetank-swiper>
	<zetank-swiper :height="200" :radius="20" :imageList="imageList" type="card" @clickImg="clickImg"></zetank-swiper>
</view>

```

```js

data() {
	return {
		imageList:[{src:'/static/banner.jpg',content:"图片说明"},{src:'/static/banner.jpg',content:"图片说明"}]
	}
},

```



###属性

|  属性名	|    类型	| 默认值		| 说明															|
| :-:		| :-:		| :-:			| :-:														|
| 	imageList		| 	Array		| 	-			| 			图片url		[{src:"图片url"}]										|
| 	type		| 	String		| 		default		| 			类型	(default,default-nav,card)				|
| 	dotsStyles		| 	Object		| 				| 			轮播点样式				|
| 	height		| 	Number		| 		200		| 			图片高度					|
| 	autoplay		| 	Boolean		| 		true		| 			是否自动滚动			|
| 	circular		| 	Boolean		| 		true		| 			是否循环滚动				|
| 	showBG		| 	Boolean		| 		false		| 			是否显示背景					|
| 	bg		| 	String		| 				| 			背景url				|
| 	radius		| 	Number		| 		0		| 			图片圆角				|
| 	previousNext		| 	Number		| 		20		| 			图片左右之间距离，当type=	card时生效			|
| 	@clickImg		| 	Fuction		| 	-			| 			点击图片事件												|