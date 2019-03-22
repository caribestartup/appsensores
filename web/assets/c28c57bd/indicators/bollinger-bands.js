/*
  Highcharts JS v7.0.3 (2019-02-06)

 Indicator series type for Highstock

 (c) 2010-2019 Pawe Fus

 License: www.highcharts.com/license
*/
(function(a){"object"===typeof module&&module.exports?(a["default"]=a,module.exports=a):"function"===typeof define&&define.amd?define(function(){return a}):a("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(a){var p=function(c){var a=c.each,z=c.merge,q=c.error,r=c.defined,l=c.seriesTypes.sma;return{pointArrayMap:["top","bottom"],pointValKey:"top",linesApiNames:["bottomLine"],getTranslatedLinesNames:function(b){var f=[];a(this.pointArrayMap,function(d){d!==b&&f.push("plot"+d.charAt(0).toUpperCase()+
d.slice(1))});return f},toYData:function(b){var f=[];a(this.pointArrayMap,function(d){f.push(b[d])});return f},translate:function(){var b=this,f=b.pointArrayMap,d=[],c,d=b.getTranslatedLinesNames();l.prototype.translate.apply(b,arguments);a(b.points,function(l){a(f,function(a,f){c=l[a];null!==c&&(l[d[f]]=b.yAxis.toPixels(c,!0))})})},drawGraph:function(){var b=this,f=b.linesApiNames,d=b.points,c=d.length,n=b.options,t=b.graph,g={options:{gapSize:n.gapSize}},e=[],h=b.getTranslatedLinesNames(b.pointValKey),
k;a(h,function(b,a){for(e[a]=[];c--;)k=d[c],e[a].push({x:k.x,plotX:k.plotX,plotY:k[b],isNull:!r(k[b])});c=d.length});a(f,function(a,c){e[c]?(b.points=e[c],n[a]?b.options=z(n[a].styles,g):q('Error: "There is no '+a+' in DOCS options declared. Check if linesApiNames are consistent with your DOCS line names." at mixin/multiple-line.js:34'),b.graph=b["graph"+a],l.prototype.drawGraph.call(b),b["graph"+a]=b.graph):q('Error: "'+a+" doesn't have equivalent in pointArrayMap. To many elements in linesApiNames relative to pointArrayMap.\"")});
b.points=d;b.options=n;b.graph=t;l.prototype.drawGraph.call(b)}}}(a);(function(a,p){var c=a.merge,q=a.isArray,r=a.seriesTypes.sma;a.seriesType("bb","sma",{params:{period:20,standardDeviation:2,index:3},bottomLine:{styles:{lineWidth:1,lineColor:void 0}},topLine:{styles:{lineWidth:1,lineColor:void 0}},tooltip:{pointFormat:'\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e\x3cb\x3e {series.name}\x3c/b\x3e\x3cbr/\x3eTop: {point.top}\x3cbr/\x3eMiddle: {point.middle}\x3cbr/\x3eBottom: {point.bottom}\x3cbr/\x3e'},
marker:{enabled:!1},dataGrouping:{approximation:"averages"}},a.merge(p,{pointArrayMap:["top","middle","bottom"],pointValKey:"middle",nameComponents:["period","standardDeviation"],linesApiNames:["topLine","bottomLine"],init:function(){r.prototype.init.apply(this,arguments);this.options=c({topLine:{styles:{lineColor:this.color}},bottomLine:{styles:{lineColor:this.color}}},this.options)},getValues:function(a,b){var c=b.period,d=b.standardDeviation,l=a.xData,n=(a=a.yData)?a.length:0,t=[],g,e,h,k,p=[],
w=[],x,m;if(l.length<c)return!1;x=q(a[0]);for(m=c;m<=n;m++){k=l.slice(m-c,m);e=a.slice(m-c,m);g=r.prototype.getValues.call(this,{xData:k,yData:e},b);k=g.xData[0];g=g.yData[0];h=0;for(var y=e.length,u=0,v;u<y;u++)v=(x?e[u][b.index]:e[u])-g,h+=v*v;h=Math.sqrt(h/(y-1));e=g+d*h;h=g-d*h;t.push([k,e,g,h]);p.push(k);w.push([e,g,h])}return{values:t,xData:p,yData:w}}}))})(a,p)});
//# sourceMappingURL=bollinger-bands.js.map