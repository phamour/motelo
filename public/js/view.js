var cyStyle = cytoscape.stylesheet()
    .selector('node')
        .css({
            'content': 'data(id)',
            'background-color': '#61bffc',
            'width': 15,
            'height': 15,
            'text-valign': 'center'
        })
    .selector('.normal')
        .css({
            'content': 'data(label)',
            'target-arrow-shape': 'triangle',
            'width': 1,
            'line-color': '#61bffc',
            'target-arrow-color': '#61bffc'
        })
    .selector('.blocked')
        .css({
            'content': 'data(label)',
            'target-arrow-shape': 'triangle',
            'width': 1,
            'line-color': '#e74c3c',
            'target-arrow-color': '#e74c3c'
        })
    .selector('.reversed')
        .css({
            'content': 'data(label)',
            'source-arrow-shape': 'triangle',
            'width': 1,
            'line-color': '#f1c40f',
            'source-arrow-color': '#f1c40f'
        })
    .selector('.od_path_normal')
        .css({
            'content': 'data(label)',
            'target-arrow-shape': 'triangle',
            'width': 2,
            'line-color': '#2ecc71',
            'target-arrow-color': '#2ecc71'
        })
    .selector('.od_path_reversed')
        .css({
            'content': 'data(label)',
            'source-arrow-shape': 'triangle',
            'width': 2,
            'line-color': '#2ecc71',
            'source-arrow-color': '#2ecc71'
        });

//var cyLayout = {
//    name: 'breadthfirst',
//    directed: true,
//    roots: '#1',
//    padding: 10
//}

var testgraph = {
    nodes: [],
    edges: [],
    metadata: {
        n: 0,
        m: 0,
        O: -1,
        D: -1,
        blockage: []
    }
};
var resultgraph = {
    nodes: [],
    edges: [],
    metadata: {
        n: 0,
        m: 0,
        O: -1,
        D: -1,
        blockage: []
    }
};

var cyTestgraph, cyResultgraph;

var edges = [];

$.ajax({
    url: $('#instance_url').val(),
    dataType: 'json',
    type: 'GET',
    async: false,
    success: function(dat) {
        // display instance file content
        if ($('#instance_content') !== undefined) {
            $('textarea', $('#instance_content')).val(dat.content);
        }

        testgraph.metadata.n = dat.n;
        testgraph.metadata.m = dat.m;
        testgraph.metadata.O = dat.O;
        testgraph.metadata.D = dat.D;
        testgraph.metadata.blockage = dat.blockage;

        // parse nodes
        for (var i = 0; i < dat.n; i++) {
            testgraph.nodes.push({data: {id: '' + (i + 1)}});
        }

        // parse edges
        for (var i = 0; i < dat.m; i++) {
            var source = dat.arcs[i][0];
            var target = dat.arcs[i][1];
            var weight = dat.arcs[i][2];
            testgraph.edges.push({
                data: {
                    id: source + '-' + target,
                    weight: weight,
                    source: '' + source,
                    target: '' + target,
                    label: '' + weight
                },
                classes: 'normal'
            });
        }

        resultgraph = testgraph;

        // create cytoscape graph
        cyTestgraph = cytoscape({
            container: document.getElementById('test_case')
                .getElementsByClassName('graph_wrapper')[0],
            style: cyStyle,
            elements: testgraph,
            layout: {
                name: 'grid'
            }
        });

        // highlight blockage
        cyTestgraph.getElementById(dat.blockage[0] + '-' + dat.blockage[1])
            .addClass('blocked');

        // hide loading image
        $('#test_case .loading_img').hide();
    }
});

if ($('#result_graph') !== undefined) {
    $.ajax({
        url: $('#solution_url').val(),
        dataType: 'json',
        type: 'GET',
        async: false,
        success: function(sol) {
            // display solution file content
            $('textarea', $('#result_content')).val(sol.content);

            // create cytoscape graph
            cyResultgraph = cytoscape({
                container: document.getElementById('result_graph')
                    .getElementsByClassName('graph_wrapper')[0],
                style: cyStyle,
                elements: resultgraph,
                layout: {
                    name: 'grid'
                }
            });

            // highlight blockage
            cyResultgraph.getElementById(resultgraph.metadata.blockage[0] + '-' + resultgraph.metadata.blockage[1])
                .addClass('blocked');

            // highlight reversals
            for (var i in sol.x) {
                for (var j in sol.x[i]) {
                    if (sol.x[i][j] === '1') {
                        cyResultgraph.getElementById(i + '-' + j)
                            .removeClass('normal')
                            .addClass('reversed');
                    }
                }
            }

            // OD pairs
            var odOptions = '';
            for (var i = 0; i < resultgraph.metadata.n; i++) {
                for (var j = 0; j < resultgraph.metadata.n; j++) {
                    if (i !== j) {
                        var value = (i+1) + '-' + (j+1);
                        odOptions += '<option>' + value + '</option>';
                    }
                }
            }
            $('#odpairs').append(odOptions).on('change', function(){
                resetPath(edges);
                edges = [];

                var od = $(this).val().split('-');
                if (od[0] !== '') {
                    var flows = sol.y[od[0]][od[1]];
                    for (var i in flows) {
                        for (var j in flows[i]) {
                            edges.push(i + '-' + j);
                        }
                    }
                    highlightPath(edges);
                }
            });

            // hide loading image
            $('#result_graph .loading_img').hide();
        }
    });
}

function highlightEdge(id, originalClass){
    cyResultgraph.getElementById(id)
        .removeClass(originalClass)
        .addClass('od_path_' + originalClass);
}

function resetEdge(id, originalClass){
    cyResultgraph.getElementById(id)
        .removeClass('od_path_' + originalClass)
        .addClass(originalClass);
}

function highlightPath(edges){
    for (var i = 0; i < edges.length; i++) {
        if (cyResultgraph.getElementById(edges[i]).hasClass('reversed')) {
            highlightEdge(edges[i], 'reversed');
        } else {
            highlightEdge(edges[i], 'normal');
        }
    }
}

function resetPath(edges){
    for (var i = 0; i < edges.length; i++) {
        if (cyResultgraph.getElementById(edges[i]).hasClass('od_path_reversed')) {
            resetEdge(edges[i], 'reversed');
        } else {
            resetEdge(edges[i], 'normal');
        }
    }
}

// END /public/js/view.js
