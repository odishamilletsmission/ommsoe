
var $ = jQuery.noConflict();

Chart.pluginService.register({
    beforeDraw: function (chart) {
        if (chart.config.options.elements.center) {
            //Get ctx from string
            var ctx = chart.chart.ctx;

            //Get options from the center object in options
            var centerConfig = chart.config.options.elements.center;
            var fontStyle = centerConfig.fontStyle || 'Arial';
            var txt = centerConfig.text;
            var color = centerConfig.color || '#000';
            var sidePadding = centerConfig.sidePadding || 20;
            var sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2)
            //Start with a base font of 30px
            ctx.font = "30px " + fontStyle;

            //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
            var stringWidth = ctx.measureText(txt).width;
            var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

            // Find out how much the font can grow in width.
            var widthRatio = elementWidth / stringWidth;
            var newFontSize = Math.floor(30 * widthRatio);
            var elementHeight = (chart.innerRadius * 2);

            // Pick a new font size so it will not be larger than the height of label.
            var fontSizeToUse = Math.min(newFontSize, elementHeight);

            //Set font settings to draw it correctly.
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
            var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
            ctx.font = fontSizeToUse+"px " + fontStyle;
            ctx.fillStyle = color;

            //Draw text in center
            ctx.fillText(txt, centerX, centerY);
        }
    }
});

Chart.defaults.groupableBar = Chart.helpers.clone(Chart.defaults.bar);

var helpers = Chart.helpers;
Chart.controllers.groupableBar = Chart.controllers.bar.extend({
    calculateBarX: function (index, datasetIndex) {
        // position the bars based on the stack index
        var stackIndex = this.getMeta().stackIndex;
        return Chart.controllers.bar.prototype.calculateBarX.apply(this, [index, stackIndex]);
    },

    hideOtherStacks: function (datasetIndex) {
        var meta = this.getMeta();
        var stackIndex = meta.stackIndex;

        this.hiddens = [];
        for (var i = 0; i < datasetIndex; i++) {
            var dsMeta = this.chart.getDatasetMeta(i);
            if (dsMeta.stackIndex !== stackIndex) {
                this.hiddens.push(dsMeta.hidden);
                dsMeta.hidden = true;
            }
        }
    },

    unhideOtherStacks: function (datasetIndex) {
        var meta = this.getMeta();
        var stackIndex = meta.stackIndex;

        for (var i = 0; i < datasetIndex; i++) {
            var dsMeta = this.chart.getDatasetMeta(i);
            if (dsMeta.stackIndex !== stackIndex) {
                dsMeta.hidden = this.hiddens.unshift();
            }
        }
    },

    calculateBarY: function (index, datasetIndex) {
        this.hideOtherStacks(datasetIndex);
        var barY = Chart.controllers.bar.prototype.calculateBarY.apply(this, [index, datasetIndex]);
        this.unhideOtherStacks(datasetIndex);
        return barY;
    },

    calculateBarBase: function (datasetIndex, index) {
        this.hideOtherStacks(datasetIndex);
        var barBase = Chart.controllers.bar.prototype.calculateBarBase.apply(this, [datasetIndex, index]);
        this.unhideOtherStacks(datasetIndex);
        return barBase;
    },

    getBarCount: function () {
        var stacks = [];

        // put the stack index in the dataset meta
        Chart.helpers.each(this.chart.data.datasets, function (dataset, datasetIndex) {
            var meta = this.chart.getDatasetMeta(datasetIndex);
            if (meta.bar && this.chart.isDatasetVisible(datasetIndex)) {
                var stackIndex = stacks.indexOf(dataset.stack);
                if (stackIndex === -1) {
                    stackIndex = stacks.length;
                    stacks.push(dataset.stack);
                }
                meta.stackIndex = stackIndex;
            }
        }, this);

        this.getMeta().stacks = stacks;
        return stacks.length;
    },
});