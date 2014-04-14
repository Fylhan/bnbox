{% if nbPage > 1 %}
<p class="pagination paginationHaut">
	Page <strong>{{page}}</strong> sur <strong>{{nbPage}}</strong> | <strong>{{nbElement}}</strong> {{appellationElement}}{{ nbElement > 0 ? 's' : ''}}
	{% if 1 != page or page != nbPage %}
		<span>
		{% if 1 != page %}
			<a href="{{ 2 != page ? page-1~'/' : '' }}{{UrlCourant}}{{UrlTri}}" title="Page n°{{page-1}}">&laquo; précédents</a>
		{% endif %}
		{% if 1 != page and page != nbPage %}
		 | 
		{% endif %}
		{% if page != nbPage %}
			 <a href="{{page+1}}/{{UrlCourant}}{{UrlTri}}" title="Page n°{{page+1}}">suivants &raquo;</a>
		{% endif %}
		</span>
	{% endif %}
</p>
{% endif %}