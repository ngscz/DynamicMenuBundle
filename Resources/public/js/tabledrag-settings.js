var tableDragSettings = {
    'menu-item-parent-id': [
        {
            'target': 'menu-item-parent-id',
            'source': 'menu-item-id',
            'relationship': 'parent',
            'action': 'match',
            'hidden': true,
            'limit': 9
        }
    ],
    'menu-item-left': [
        {
            'target': 'menu-item-left',
            'source': 'menu-item-left',
            'relationship': 'self',
            'action': 'nestedSet',
            'hidden': true,
            'limit': 0
        }
    ]
};
